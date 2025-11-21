<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BuyerResource;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $query = Buyer::query();
        if ($request->filled('byr_type')) {
            $query->where('byr_type', $request->byr_type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('byr_name', 'like', "%{$search}%")
                    ->orWhere('byr_ntn_cnic', 'like', "%{$search}%")
                    ->orWhere('byr_address', 'like', "%{$search}%");
            });
        }
        $buyers = $query->latest()->paginate(10);
        foreach ($buyers as $buyer) {
            $calculatedHash = $buyer->generateHash();
            $buyer->tampered = $calculatedHash !== $buyer->hash;
        }
        if (isApiRequest()) {
            $buyersResponse = BuyerResource::collection($buyers);
            return successResponse($buyersResponse,  200 , 'Buyers Data Fetched');
        }
        return view('buyers.index', compact('buyers'));
    }
    public function fetch($id)
    {
        return response()->json(Buyer::findOrFail($id));
    }
    public function create()
    {
        return view('buyers.create');
    }
    public function store(Request $request)
    {
        $isApiRequest = isApiRequest();
        $rules = [
            'byr_name' => 'required|string|max:255',
            'byr_type' => 'required|integer',
            'byr_id_type' => [
                'nullable',
                'required_if:byr_type,1',
                'in:NTN,CNIC'
            ],
            'byr_ntn_cnic' => [
                'nullable',
                'required_if:byr_type,1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->byr_id_type === 'NTN' && !preg_match('/^[0-9]{7}$/', $value)) {
                        $fail('Client NTN must be exactly 7 digits.');
                    }
                    if ($request->byr_id_type === 'CNIC' && !preg_match('/^[0-9]{13}$/', $value)) {
                        $fail('Client CNIC must be exactly 13 digits (without dashes).');
                    }
                }
            ],
            'byr_address' => 'required|string',
            'byr_province' => 'required|string',
            'byr_account_title' => 'nullable|string|max:255',
            'byr_account_number' => 'nullable|string|max:255',
            'byr_reg_num' => 'nullable|string|max:255',
            'byr_contact_num' => 'nullable|string|max:20',
            'byr_contact_person' => 'nullable|string|max:255',
            'byr_IBAN' => 'nullable|string|max:255',
            'byr_acc_branch_name' => 'nullable|string|max:255',
            'byr_acc_branch_code' => 'nullable|string|max:255',
            'byr_logo' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($isApiRequest) {
                return errorResponse($validator->errors(), 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
            try {
                Log::info('Client logo upload started', [
                    'disk' => $disk,
                    'has_file' => $request->hasFile('byr_logo'),
                ]);
                if ($request->hasFile('byr_logo')) {
                    $file = $request->file('byr_logo');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $folder = 'buyer_images';
                    $options = [];
                    Log::info('S3 config check', [
                        'bucket' => config('filesystems.disks.s3.bucket'),
                        'region' => config('filesystems.disks.s3.region'),
                        'url' => config('filesystems.disks.s3.url'),
                    ]);
                    $path = Storage::disk($disk)->putFileAs($folder, $file, $filename, $options);
                    if ($path) {
                        Log::info('File uploaded successfully', ['path' => $path]);
                        $validated['byr_logo'] = $path;
                    } else {
                        Log::error('File upload failed: putFileAs returned false');
                        $validated['byr_logo'] = '';
                    }
                } else {
                    Log::warning('No byr_logo file found in request');
                    $validated['byr_logo'] = '';
                }
            } catch (\Throwable $e) {
                Log::error('Error during Client logo upload', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $validated['byr_logo'] = '';
            }
            $normalized = collect($validated)->map(fn($v) => $v ?? '')->toArray();
            $buyer = Buyer::create($normalized);
            logActivity(
                'add',
                'Added new client: ' . $buyer->byr_name,
                $buyer->toArray(),
                $buyer->id,
                'buyers'
            );
            DB::commit();
            if ($isApiRequest) {
                return successResponse($buyer, 200, 'Client created successfully');
            }
            return redirect()->route('buyers.index')
                ->with('message', 'Client created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation failed during buyer creation', [
                'errors' => $e->errors(),
            ]);
            if ($isApiRequest) {
                return errorResponse($e->errors(), 422);
            }
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Unexpected error during Client creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            if ($isApiRequest) {
                return errorResponse($e->getMessage(), 500);
            }
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        $decryptedId = Crypt::decryptString($id);
        $buyer = Buyer::findOrFail($decryptedId);
        return view('buyers.edit', compact('buyer'));
    }
    public function update(Request $request)
    {
        $isApiRequest = isApiRequest();
        $rules = [
            'byr_id' => 'required',
            'byr_name' => 'required|string|max:255',
            'byr_type' => 'required|integer',
            'byr_id_type' => [
                'nullable',
                'required_if:byr_type,1',
                'in:NTN,CNIC'
            ],
            'byr_ntn_cnic' => [
                'nullable',
                'required_if:byr_type,1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->byr_id_type === 'NTN' && !preg_match('/^[0-9]{7}$/', $value)) {
                        $fail('Client NTN must be exactly 7 digits.');
                    }
                    if ($request->byr_id_type === 'CNIC' && !preg_match('/^[0-9]{13}$/', $value)) {
                        $fail('Client CNIC must be exactly 13 digits (without dashes).');
                    }
                }
            ],
            'byr_address' => 'required|string',
            'byr_province' => 'required|string',
            'byr_account_title' => 'nullable|string|max:255',
            'byr_account_number' => 'nullable|string|max:255',
            'byr_reg_num' => 'nullable|string|max:255',
            'byr_contact_num' => 'nullable|string|max:20',
            'byr_contact_person' => 'nullable|string|max:255',
            'byr_IBAN' => 'nullable|string|max:255',
            'byr_acc_branch_name' => 'nullable|string|max:255',
            'byr_acc_branch_code' => 'nullable|string|max:255',
            'byr_logo' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($isApiRequest) {
                return errorResponse($validator->errors(), 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $byr_id = $request->byr_id;
            if (!$isApiRequest) {
                $byr_id = Crypt::decryptString($request->byr_id);
            }
            $buyer = Buyer::findOrFail($byr_id);
            $oldData = $buyer->toArray();
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
            if ($request->hasFile('byr_logo')) {
                $folder = 'buyer_images';
                if (!empty($buyer->byr_logo) && Storage::disk($disk)->exists($buyer->byr_logo)) {
                    Storage::disk($disk)->delete($buyer->byr_logo);
                }
                $file      = $request->file('byr_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;
                $path = Storage::disk($disk)->putFileAs($folder, $file, $filename);
                if ($path) {
                    $validated['byr_logo'] = $path;
                } else {
                    $validated['byr_logo'] = $buyer->byr_logo ?? '';
                    Log::error('❌ Buyer logo update failed', [
                        'disk'     => $disk,
                        'folder'   => $folder,
                        'filename' => $filename,
                    ]);
                }
            } else {
                $validated['byr_logo'] = $buyer->byr_logo ?? '';
            }
            $normalized = collect($validated)->map(fn($v) => $v ?? '')->toArray();
            $buyer->update($normalized);
            logActivity(
                'update',
                'Updated client: ' . $buyer->byr_name,
                ['old' => $oldData, 'new' => $buyer->toArray()],
                $buyer->id,
                'buyers'
            );
            DB::commit();
            /** Return API response */
            if ($isApiRequest) {
                return successResponse(
                    data: $buyer,
                    status: 200,
                    message: 'Client updated successfully'
                );
            }
            return redirect()->route('buyers.index')
                ->with('message', 'Client updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if ($isApiRequest) {
                return errorResponse(
                    message: $e->getMessage(),
                    status: 422
                );
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($isApiRequest) {
                return errorResponse(
                    message: $e->getMessage(),
                    status: 500
                );
            }
            return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        }
    }
    public function delete(Request $request)
    {
        try {
            $isApiRequest = isApiRequest();
            $byr_id = $request->byr_id;
            if (!$isApiRequest) {
                $byr_id = Crypt::decryptString($request->byr_id);
            }
            $buyer = Buyer::findOrFail($byr_id);
            // Prevent delete if buyer has invoices
            if ($buyer->invoices()->exists()) {
                $msg = 'This client cannot be deleted because invoices are associated with it.';
                if ($isApiRequest) {
                    return errorResponse($msg, 400);
                }
                // Web response
                $validator = Validator::make([], []);
                $validator->errors()->add('toast_error', $msg);
                return redirect()
                    ->route('buyers.index')
                    ->withErrors($validator);
            }
            // Proceed with delete
            $oldData = $buyer->toArray();
            $buyerName = $oldData['byr_name'] ?? '';
            $buyer->delete();
            logActivity(
                'delete',
                'Deleted client: ' . $buyerName,
                $oldData,
                $byr_id,
                'buyers'
            );
            if ($isApiRequest) {
                return successResponse([], 200, 'Client deleted successfully');
            }
            return redirect()
                ->route('buyers.index')
                ->with('message', 'Client deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($isApiRequest) {
                return errorResponse('Client not found', 404);
            }
            return redirect()
                ->route('buyers.index')
                ->withErrors(['toast_error' => 'Client not found']);
        } catch (\Exception $e) {
            if ($isApiRequest) {
                return errorResponse($e->getMessage(), 500);
            }
            return redirect()
                ->route('buyers.index')
                ->withErrors(['toast_error' => $e->getMessage()]);
        }
    }
    public function fetchBuyer(Request $request)
    {
        try {
            $byr_id = $request->byr_id;
            $buyer = Buyer::findOrFail($byr_id);
            return successResponse(
                data: $buyer,
                status: 200,
                message: 'Client fetched successfully'
            );
        } catch (\Exception $e) {
            return errorResponse(
                message: 'Client not found',
                status: 404
            );
        }
    }
}
