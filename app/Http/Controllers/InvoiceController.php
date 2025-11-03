<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Buyer;
use App\Models\BusinessConfiguration;
use App\Models\Item;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use App\Services\FbrInvoiceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\FbrPostError;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $fbrEnv = getFbrEnv();
        $query = Invoice::with(['buyer', 'seller', 'details.item'])->where('fbr_env', $fbrEnv);
        if ($request->filled('invoice_type')) {
            $query->where('invoice_type', trim($request->invoice_type));
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('invoice_date', [
                Carbon::parse($request->date_from)->toDateString(),
                Carbon::parse($request->date_to)->toDateString(),
            ]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', Carbon::parse($request->date_from)->toDateString());
        } elseif ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', Carbon::parse($request->date_to)->toDateString());
        }
        if ($request->has('is_posted_to_fbr') && $request->is_posted_to_fbr !== '' && $request->is_posted_to_fbr !== null) {
            $query->where('is_posted_to_fbr', $request->is_posted_to_fbr);
        }
        $invoices = $query->orderByDesc('invoice_id')->paginate(10);
        foreach ($invoices as $invoice) {
            $invoice->tampered = $invoice->isTampered();
            $tamperedLines = false;
            foreach ($invoice->details as $detail) {
                if ($detail->isTampered()) {
                    $tamperedLines = true;
                    break;
                }
            }
            $invoice->tampered_lines = $tamperedLines;
            $invoice->fbr_environment = $invoice->fbr_env;
        }
        return view('invoices.index', compact('invoices'));
    }
    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
        $seller = BusinessConfiguration::where('bus_config_id', $tenantId)->first();
        if (! $seller) {
            return redirect()->route('company.configuration')
                ->with('error', 'Please configure your business first before creating an invoice.');
        }
        $buyers = Buyer::all();
        $items = Item::all();
        return view('invoices.create', compact('seller', 'buyers', 'items'));
    }
    public function storeFbrError(string $type, array $response)
    {
        try {
            DB::table('fbr_post_error')->insert([
                'type' => $type,
                'status_code' => $response['data']['validationResponse']['statusCode'] ?? null,
                'status' => $response['data']['validationResponse']['status'] ?? null,
                'error_code' => $response['data']['validationResponse']['errorCode'] ?? null,
                'error' => $response['error']
                    ?? ($response['data']['validationResponse']['error'] ?? 'Unknown error'),
                'invoice_statuses' => !empty($response['invoiceStatuses'])
                    ? json_encode($response['invoiceStatuses'], JSON_UNESCAPED_UNICODE)
                    : json_encode([]),
                'raw_response' => json_encode($response, JSON_UNESCAPED_UNICODE),
                'error_time' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("FBR Error store failed: " . $e->getMessage(), [
                'response' => $response
            ]);
        }
    }
    public function filter(Request $request)
    {
        return redirect()->route('invoices.index', [
            'invoice_type' => $request->invoice_type,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'is_posted_to_fbr' => $request->is_posted_to_fbr,
        ]);
    }
    public function storeOrUpdate(Request $request, $id = null)
    {
        set_time_limit(120);
        $data = $request->only([
            'invoiceType',
            'invoiceDate',
            'due_date',
            'scenarioId',
            'invoiceRefNo',
            'seller_id',
            'byr_id',
            'buyerRegistrationType',
            'sellerNTNCNIC',
            'sellerBusinessName',
            'sellerProvince',
            'sellerAddress',
            'buyerNTNCNIC',
            'buyerProvince',
            'buyerBusinessName',
            'buyerAddress',
            'totalAmountExcludingTax',
            'totalAmountIncludingTax',
            'totalSalesTax',
            'SalesTaxApplicable',
            'totalfurtherTax',
            'totalextraTax',
            'shipping_charges',
            'other_charges',
            'discount_amount',
            'payment_status',
            'notes',
            'items',
            'invoice_status'
        ]);
        $filteredItems = array_filter($data['items'] ?? [], function ($item) {
            return isset($item['item_id'], $item['quantity'], $item['totalValues']);
        });
        $data['items'] = array_values($filteredItems);
        $request->merge(['items' => $data['items']]);
        $request->validate([
            'invoiceType' => 'required|string',
            'invoiceDate' => 'required|date',
            'seller_id' => [
                'required',
                'integer',
                Rule::exists('business_configurations', 'bus_config_id')
                    ->where(fn($query) => $query->where('bus_config_id', session('tenant_id'))),
            ],
            'buyerRegistrationType' => 'required|string',
            'items.*.item_id' => 'required|integer',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.totalValues' => 'required|numeric',
            'items.*.valueSalesExcludingST' => 'required|numeric',
            'items.*.SalesTaxApplicable' => 'required|numeric',
            'items.*.SalesTaxWithheldAtSource' => 'required|numeric',
            'items.*.saleType' => 'required|string',
            'items.*.productDescription' => 'required|string',
            'items.*.rate' => 'required|string',
            'items.*.uoM' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $isDraft = $data['invoice_status'] == 1;
            $invoice = $id ? Invoice::findOrFail($id) : new Invoice();
            if ($id && $invoice->is_posted_to_fbr) {
                return back()->with('error', 'You cannot update an invoice that is already posted to FBR.');
            }
            $invoice->fill([
                'invoice_type' => $data['invoiceType'],
                'invoice_date' => $data['invoiceDate'],
                'due_date' => $data['due_date'],
                'scenario_id' => $data['scenarioId'] ?? null,
                'invoice_ref_no' => $data['invoiceRefNo'] ?? null,
                'seller_id' => $data['seller_id'],
                'buyer_id' => $data['byr_id'],
                'is_posted_to_fbr' => 0,
                'totalAmountExcludingTax' => $data['totalAmountExcludingTax'] ?? 0,
                'totalAmountIncludingTax' => $data['totalAmountIncludingTax'] ?? 0,
                'totalSalesTax' => $data['totalSalesTax'] ?? 0,
                'totalfurtherTax' => $data['totalfurtherTax'] ?? 0,
                'totalextraTax' => $data['totalextraTax'] ?? 0,
                'shipping_charges' => $data['shipping_charges'] ?? null,
                'other_charges' => $data['other_charges'] ?? null,
                'discount_amount' => $data['discount_amount'] ?? null,
                'payment_status' => $data['payment_status'] ?? null,
                'notes' => $data['notes'] ?? null,
                'fbr_env' => getFbrEnv(),
            ]);
            $invoice->save();
            if (!$isDraft && !$invoice->invoice_no) {
                $invoice->update([
                    'invoice_no' => 'INV-' . str_pad($invoice->invoice_id, 6, '0', STR_PAD_LEFT)
                ]);
            }
            if ($id) {
                InvoiceDetail::where('invoice_id', $invoice->invoice_id)->delete();
            }
            foreach ($data['items'] as $item) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->invoice_id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'total_value' => $item['totalValues'],
                    'value_excl_tax' => $item['valueSalesExcludingST'],
                    'retail_price' => $item['fixedNotifiedValueOrRetailPrice'] ?? 0,
                    'sales_tax_applicable' => $item['SalesTaxApplicable'],
                    'sales_tax_withheld' => $item['SalesTaxWithheldAtSource'],
                    'extra_tax' => $item['extraTax'] ?? 0,
                    'further_tax' => $item['furtherTax'] ?? 0,
                    'fed_payable' => $item['fedPayable'] ?? 0,
                    'discount' => $item['discount'] ?? 0,
                    'sale_type' => $item['saleType'],
                    'sro_schedule_no' => $item['sroScheduleNo'] ?? '',
                    'sro_item_serial_no' => $item['sroItemSerialNo'] ?? '',
                ]);
            }
            if (!$isDraft) {
                $fbrErrors   = [];
                $userErrors  = [];
                $fbrPayload = [
                    'invoiceType' => $data['invoiceType'] === 'Sales Invoice' ? 'Sale Invoice' : $data['invoiceType'],
                    'invoiceDate' => $data['invoiceDate'],
                    'sellerNTNCNIC' => preg_replace('/\D/', '', $data['sellerNTNCNIC']),
                    'sellerBusinessName' => $data['sellerBusinessName'],
                    'sellerProvince' => $data['sellerProvince'],
                    'sellerAddress' => $data['sellerAddress'],
                    'buyerNTNCNIC' => (string) $data['buyerNTNCNIC'] ?? '',
                    'buyerBusinessName' => $data['buyerBusinessName'],
                    'buyerProvince' => $data['buyerProvince'],
                    'buyerAddress' => $data['buyerAddress'],
                    'buyerRegistrationType' => $data['buyerRegistrationType'],
                    'invoiceRefNo' => $data['invoiceRefNo'] ?? '',
                    'scenarioId' => $data['scenarioId'],
                    'items' => array_map(function ($item) {
                        return [
                            'hsCode' => $item['hsCode'],
                            'productDescription' => $item['productDescription'],
                            'rate' => str_ends_with($item['rate'], '%') ? $item['rate'] : $item['rate'] . '%',
                            'uoM' => $item['uoM'],
                            'quantity' => (int) $item['quantity'],
                            'totalValues' => (float) $item['totalValues'],
                            'valueSalesExcludingST' => (float) $item['valueSalesExcludingST'],
                            'fixedNotifiedValueOrRetailPrice' => (float) $item['fixedNotifiedValueOrRetailPrice'],
                            'salesTaxApplicable' => (float) ($item['SalesTaxApplicable'] ?? 0),
                            'salesTaxWithheldAtSource' => (float) ($item['SalesTaxWithheldAtSource'] ?? 0),
                            'extraTax' =>  $item['extraTax'] ?? '',
                            'furtherTax' => (float) $item['furtherTax'],
                            'sroScheduleNo' => $item['sroScheduleNo'] ?? '',
                            'fedPayable' => (float) $item['fedPayable'],
                            'discount' => (float) $item['discount'],
                            'saleType' => $item['saleType'],
                            'sroItemSerialNo' => $item['sroItemSerialNo'] ?? '',
                        ];
                    }, $data['items']),
                ];
                $fbrService = new FbrInvoiceService();
                $validation = $fbrService->validateInvoice($fbrPayload);
                $validationPassed = false;
                if (!$validation['success']) {
                    $fbrErrors[] = [
                        'type'             => 'validation',
                        'status_code'      => $validation['statusCode'] ?? null,
                        'status'           => $validation['status'] ?? 'failed',
                        'error_code'       => $validation['errorCode'] ?? null,
                        'error'            => $validation['error'] ?? 'Unknown error',
                        'invoice_statuses' => $validation['invoiceStatuses'] ?? null,
                        'raw_response'     => $validation['data'] ?? null,
                        'fbr_env'          => getFbrEnv(),
                    ];
                    $userErrors[] = 'FBR Validation Failed: ' . (
                        $validation['error']
                        ?? ($validation['invoiceStatuses'][0]['error'] ?? 'Unknown validation error'));
                    Log::info('FBR Payload during Validation', [
                        'fbrPayload'   => $fbrPayload
                    ]);
                    DB::rollBack();
                } else {
                    $validationPassed = true;
                }
                if ($validationPassed) {
                    $posting = $fbrService->postInvoice($fbrPayload);
                    if (!$posting['success']) {
                        $fbrErrors[] = [
                            'type'             => 'posting',
                            'status_code'      => $posting['statusCode'] ?? null,
                            'status'           => $posting['status'] ?? 'failed',
                            'error_code'       => $posting['errorCode'] ?? null,
                            'error'            => $posting['error'] ?? 'Unknown error',
                            'invoice_statuses' => $posting['invoiceStatuses'] ?? null,
                            'raw_response'     => $posting['data'] ?? null,
                            'fbr_env'          => getFbrEnv(),
                        ];
                        $userErrors[] = 'FBR Posting Failed: ' . (
                            $posting['error']
                            ?? ($posting['invoiceStatuses'][0]['error'] ?? 'Unknown posting error'));
                        Log::info('FBR Payload during Posting', [
                            'fbrPayload'   => $fbrPayload
                        ]);
                        DB::rollBack();
                    }
                    if ($posting['success']) {
                        $invoice->update([
                            'fbr_invoice_number' => $posting['data']['invoiceNumber'] ?? null,
                            'is_posted_to_fbr'   => 1,
                            'response_status'    => 'Success',
                            'response_message'   => 'Posted successfully to FBR ' . strtoupper(getFbrEnv()),
                        ]);
                        logActivity(
                            'update',
                            'Posted invoice to FBR: ' . $invoice->invoice_no,
                            $invoice->toArray(),
                            $invoice->invoice_no,
                            'invoices'
                        );
                        $qrData     = $posting['data']['invoiceNumber'];
                        $qrFileName = 'qr_' . $invoice->invoice_no . '_' . time() . '.png';
                        $folder     = 'qr_codes';
                        $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
                        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->data($qrData)
                            ->size(200)
                            ->build();
                        $path = $folder . '/' . $qrFileName;
                        try {
                            $uploaded = Storage::disk($disk)->put(
                                $path,
                                $result->getString(),
                                $disk === 's3' ? [] : null
                            );
                            if ($uploaded) {
                                Log::info('✅ QR code uploaded successfully', [
                                    'disk' => $disk,
                                    'path' => $path,
                                ]);
                                $invoice->update([
                                    'qr_code'        => $path,
                                    'invoice_status' => 2,
                                ]);
                            } else {
                                Log::error('❌ QR code upload failed (put returned false)', [
                                    'disk' => $disk,
                                    'path' => $path,
                                ]);
                            }
                        } catch (\Throwable $e) {
                            Log::error('❌ Error while uploading QR code', [
                                'disk'   => $disk,
                                'path'   => $path,
                                'error'  => $e->getMessage(),
                                'trace'  => $e->getTraceAsString(),
                            ]);
                        }
                    }
                }
                if (!empty($fbrErrors)) {
                    DB::transaction(function () use ($fbrErrors) {
                        foreach ($fbrErrors as $err) {
                            FbrPostError::logError($err);
                        }
                    });
                    session()->flash('fbrErrors', $fbrErrors);
                    return back()
                        ->withInput()
                        ->withErrors(['toast_error' =>  $userErrors]);
                }
            }
            DB::commit();
            return redirect()->route('invoices.index')->with('message', $id ? 'Invoice updated successfully.' : 'Invoice created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function edit($id)
    {
        $invoiceId = Crypt::decryptString($id);
        $invoice = Invoice::with(['items', 'buyer', 'seller'])->findOrFail($invoiceId);
        $buyers = Buyer::all();
        $items = Item::all();
        $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
        $seller = BusinessConfiguration::where('bus_config_id', $tenantId)->first();
        return view('invoices.create', compact('invoice', 'buyers', 'items', 'seller'));
    }
    public function delete($id)
    {
        $invoice = Invoice::with('details')->findOrFail($id);
        // Only allow delete if invoice is in draft status
        if ($invoice->invoice_status != Invoice::STATUS_DRAFT) {
            $validator = \Validator::make([], []);
            $validator->errors()->add(
                'toast_error',
                'Only draft invoices can be deleted. This invoice has already been posted.'
            );

            return redirect()
                ->route('invoices.index')
                ->withErrors($validator);
        }
        $oldData = $invoice->toArray();
        $invoice->details()->delete();
        $invoice->delete();
        logActivity(
            'delete',
            'Deleted draft invoice: ' . ($oldData['invoice_no'] ?? 'N/A'),
            $oldData,
            $id,
            'invoices'
        );
        return redirect()
            ->route('invoices.index')
            ->with('message', 'Draft invoice and its details deleted successfully.');
    }
    public function modalPreview($id)
    {
        try {
            $invoiceId = Crypt::decryptString($id);
            $invoice = Invoice::with([
                'buyer',
                'seller',
                'details.item'
            ])->findOrFail($invoiceId);
            if (request()->ajax()) {
                return view('invoices.modal_preview', [
                    'invoice' => $invoice,
                ])->render();
            }
            return view('invoices.modal_preview', [
                'invoice' => $invoice,
            ]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }
            abort(404);
        }
    }
    public function print($id)
    {
        $invoiceId = Crypt::decryptString($id);
        $invoice = Invoice::with([
            'buyer',
            'seller',
            'details.item'
        ])->findOrFail($invoiceId);
        $nonce = bin2hex(random_bytes(16));
        return view('invoices.print', [
            'invoice' => $invoice,
            'nonce'   => $nonce,
        ]);
    }
    public function download($id)
    {
        try {
            $invoiceId = Crypt::decryptString($id);
            $invoice = Invoice::with([
                'buyer',
                'seller',
                'details.item'
            ])->findOrFail($invoiceId);
            $pdf = Pdf::loadView('invoices.download_invoice', ['invoice' => $invoice]);
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="invoice.pdf"');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }
            abort(404);
        }
    }
    public function showForm()
    {
        return view('invoices.import');
    }

    public function importFromExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            // Read the Excel file — first sheet only
            $collection = Excel::toCollection(null, $request->file('excel_file'))->first();

            if ($collection->isEmpty()) {
                return back()->withErrors(['toast_error' => 'Excel file is empty.']);
            }

            // --- Convert Excel to associative array using headers from the first row ---
            $headers = $collection->shift()->toArray();
            $rows = $collection->map(function ($row) use ($headers) {
                return collect($headers)->combine($row);
            });

            // --- Group rows by invoiceRefNo (1 invoice → many rows/items) ---
            $grouped = $rows->groupBy('invoiceRefNo');
            $successCount = 0;

            DB::beginTransaction();

            foreach ($grouped as $invoiceRef => $invoiceRows) {
                $first = $invoiceRows->first();

                // Create new Invoice as Draft
                $invoice = Invoice::create([
                    'invoice_type' => $first['invoiceType'] ?? 'Standard',
                    'invoice_date' => $first['invoiceDate'] ?? now(),
                    'due_date' => $first['due_date'] ?? null,
                    'scenario_id' => $first['scenarioId'] ?? null,
                    'invoice_ref_no' => $invoiceRef,
                    'seller_id' => $first['seller_id'] ?? session('tenant_id'),
                    'buyer_id' => $first['byr_id'] ?? 1,
                    'totalAmountExcludingTax' => $first['totalAmountExcludingTax'] ?? 0,
                    'totalAmountIncludingTax' => $first['totalAmountIncludingTax'] ?? 0,
                    'totalSalesTax' => $first['totalSalesTax'] ?? 0,
                    'totalfurtherTax' => $first['totalfurtherTax'] ?? 0,
                    'totalextraTax' => $first['totalextraTax'] ?? 0,
                    'shipping_charges' => $first['shipping_charges'] ?? 0,
                    'other_charges' => $first['other_charges'] ?? 0,
                    'discount_amount' => $first['discount_amount'] ?? 0,
                    'payment_status' => $first['payment_status'] ?? null,
                    'notes' => $first['notes'] ?? null,
                    'invoice_status' => 1, // Draft
                    'is_posted_to_fbr' => 0,
                    'fbr_env' => getFbrEnv(),
                ]);

                // --- Create Invoice Line Items ---
                foreach ($invoiceRows as $row) {
                    InvoiceDetail::create([
                        'invoice_id' => $invoice->invoice_id,
                        'item_id' => $row['item_id'] ?? null,
                        'quantity' => $row['quantity'] ?? 0,
                        'total_value' => $row['totalValues'] ?? 0,
                        'value_excl_tax' => $row['valueSalesExcludingST'] ?? 0,
                        'retail_price' => $row['fixedNotifiedValueOrRetailPrice'] ?? 0,
                        'sales_tax_applicable' => $row['SalesTaxApplicable'] ?? 0,
                        'sales_tax_withheld' => $row['SalesTaxWithheldAtSource'] ?? 0,
                        'extra_tax' => $row['extraTax'] ?? 0,
                        'further_tax' => $row['furtherTax'] ?? 0,
                        'fed_payable' => $row['fedPayable'] ?? 0,
                        'discount' => $row['discount'] ?? 0,
                        'sale_type' => $row['saleType'] ?? 'Normal',
                        'sro_schedule_no' => $row['sroScheduleNo'] ?? '',
                        'sro_item_serial_no' => $row['sroItemSerialNo'] ?? '',
                    ]);
                }

                // --- Log activity ---
                logActivity(
                    'create',
                    'Imported draft invoice via Excel: ' . ($invoice->invoice_ref_no ?? $invoice->invoice_id),
                    $invoice->toArray(),
                    $invoice->invoice_id,
                    'invoices'
                );

                $successCount++;
            }

            DB::commit();

            if ($successCount === 0) {
                return back()->withErrors(['toast_error' => 'No invoices imported successfully. Please check Excel formatting.']);
            }

            return redirect()
                ->route('invoices.index')
                ->with('message', "{$successCount} invoice(s) imported successfully as drafts.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['toast_error' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    // public function importInvoice(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls',
    //     ]);
    //     $rows = Excel::toCollection(null, $request->file('excel_file'))->first();
    //     if ($rows->isEmpty()) {
    //         return back()->with('error', 'Excel file is empty.');
    //     }
    //     // Group by invoiceRefNo (one invoice, many items)
    //     $grouped = $rows->groupBy('invoiceRefNo');
    //     $successCount = 0;
    //     $failures = [];
    //     foreach ($grouped as $invoiceRef => $invoiceRows) {
    //         $first = $invoiceRows->first();
    //         // Validate required invoice-level fields
    //         $validator = Validator::make($first->toArray(), [
    //             'invoiceType' => 'required',
    //             'invoiceDate' => 'required|date',
    //             'sellerNTNCNIC' => 'required',
    //             'sellerBusinessName' => 'required',
    //             'sellerProvince' => 'required',
    //             'sellerAddress' => 'required',
    //             'scenarioId' => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             $failures[] = "InvoiceRef: {$invoiceRef} validation failed: " . implode(", ", $validator->errors()->all());
    //             continue;
    //         }
    //         $invoice = FbrInvoice::create([
    //             'invoice_ref_no' => $invoiceRef,
    //             'invoice_type' => $first['invoiceType'],
    //             'invoice_date' => $first['invoiceDate'],
    //             'seller_ntn_cnic' => $first['sellerNTNCNIC'],
    //             'seller_business_name' => $first['sellerBusinessName'],
    //             'seller_province' => $first['sellerProvince'],
    //             'seller_address' => $first['sellerAddress'],
    //             'buyer_ntn_cnic' => $first['buyerNTNCNIC'],
    //             'buyer_business_name' => $first['buyerBusinessName'],
    //             'buyer_province' => $first['buyerProvince'],
    //             'buyer_address' => $first['buyerAddress'],
    //             'buyer_registration_type' => $first['buyerRegistrationType'],
    //             'scenario_id' => $first['scenarioId'],
    //             'status' => 'draft',
    //         ]);
    //         foreach ($invoiceRows as $row) {
    //             FbrInvoiceItem::create([
    //                 'fbr_invoice_id' => $invoice->id,
    //                 'hs_code' => $row['hsCode'],
    //                 'product_description' => $row['productDescription'],
    //                 'rate' => $row['rate'],
    //                 'uo_m' => $row['uoM'],
    //                 'quantity' => $row['quantity'],
    //                 'total_values' => $row['totalValues'],
    //                 'value_sales_excluding_st' => $row['valueSalesExcludingST'],
    //                 'fixed_notified_value_or_retail_price' => $row['fixedNotifiedValueOrRetailPrice'],
    //                 'sales_tax_applicable' => $row['SalesTaxApplicable'],
    //                 'sales_tax_withheld' => $row['SalesTaxWithheldAtSource'],
    //                 'extra_tax' => $row['extraTax'],
    //                 'further_tax' => $row['furtherTax'],
    //                 'sro_schedule_no' => $row['sroScheduleNo'],
    //                 'fed_payable' => $row['fedPayable'],
    //                 'discount' => $row['discount'],
    //                 'sale_type' => $row['saleType'],
    //                 'sro_item_serial_no' => $row['sroItemSerialNo'],
    //             ]);
    //         }
    //         // Optional: Send to FBR here
    //         $sendNow = true; // Set to false if saving drafts only
    //         if ($sendNow) {
    //             $payload = $invoice->toFbrPayload();
    //             $response = Http::withToken('YOUR_ACCESS_TOKEN')
    //                 ->post('https://sandbox.fbr.gov.pk/api/invoice', $payload);
    //             if ($response->successful()) {
    //                 $invoice->update(['status' => 'sent']);
    //                 $successCount++;
    //             } else {
    //                 $invoice->update(['status' => 'failed']);
    //                 $failures[] = "InvoiceRef {$invoiceRef} failed: " . $response->body();
    //             }
    //         }
    //     }
    //     if ($failures) {
    //         return back()->with('error', "Sent: $successCount, Failed: " . count($failures) . "<br>" . implode("<br>", $failures));
    //     }
    //     return back()->with('success', "$successCount invoice(s) processed and sent.");
    // }

}
