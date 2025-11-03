<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Invoice;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        $fbrEnv = getFbrEnv();
        $totalClients = Buyer::count();
        $totalInvoices = Invoice::where('fbr_env', $fbrEnv)->count();
        $fbrPostedInvoices = Invoice::where('fbr_env', $fbrEnv)->where('is_posted_to_fbr', 1)->count();
        $fbrpostedPercentage = $totalInvoices > 0
            ? round(($fbrPostedInvoices / $totalInvoices) * 100, 2)
            : 0;
        $draftInvoices = Invoice::where('fbr_env', $fbrEnv)->where('invoice_status', Invoice::STATUS_DRAFT)->count();
        $draftPercentage = $totalInvoices > 0
            ? round(($draftInvoices / $totalInvoices) * 100, 2)
            : 0;


        // Top Five Clients - Revenue Basis
        $topClients = Buyer::select('byr_id', 'byr_name')
            ->withSum(['invoices as total' => function ($query) use ($fbrEnv) {
                $query->where('is_posted_to_fbr', 1)
                    ->where('fbr_env', $fbrEnv);
            }], 'totalAmountExcludingTax')
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topClientNames = $topClients->pluck('byr_name');
        $topClientTotals = $topClients->pluck('total');
        $totalSum = $topClientTotals->sum();
        $topClientPercentages = $topClientTotals->map(function ($val) use ($totalSum) {
            return $totalSum > 0 ? round(($val / $totalSum) * 100, 2) : 0;
        });

        // Top Five Services - Revenue Basis
        $topServicesRevenue = Item::select('items.item_id', 'items.item_description')
            ->join('invoice_details', 'invoice_details.item_id', '=', 'items.item_id')
            ->join('invoices', 'invoices.invoice_id', '=', 'invoice_details.invoice_id')
            ->where('invoices.is_posted_to_fbr', 1)
            ->where('invoices.fbr_env', $fbrEnv)
            ->groupBy('items.item_id', 'items.item_description')
            ->selectRaw('SUM(invoice_details.total_value) as total_revenue')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();
        $topServiceNamesRevenue = $topServicesRevenue->pluck('item_description');
        $topServiceTotalsRevenue = $topServicesRevenue->pluck('total_revenue')->map(fn($v) => (float)$v);
        $totalRevenueSum = $topServiceTotalsRevenue->sum();
        $topServicePercentagesRevenue = $topServiceTotalsRevenue->map(function ($val) use ($totalRevenueSum) {
            return $totalRevenueSum > 0 ? round(($val / $totalRevenueSum) * 100, 2) : 0;
        });



        // Month-wise Tax Details
        $monthlyTaxData = Invoice::selectRaw('
            MONTH(invoice_date) as month,
            SUM(totalSalesTax) as totalSalesTax,
            SUM(totalFurtherTax) as totalFurtherTax,
            SUM(totalExtraTax) as totalExtraTax
        ')
            ->whereYear('invoice_date', Carbon::now()->year)
            ->where('is_posted_to_fbr', 1)
            ->where('fbr_env', $fbrEnv)
            ->groupByRaw('MONTH(invoice_date)')
            ->orderByRaw('MONTH(invoice_date)')
            ->limit(12)
            ->get();
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $salesTaxData = array_fill(0, 12, 0);
        $furtherTaxData = array_fill(0, 12, 0);
        $extraTaxData = array_fill(0, 12, 0);
        foreach ($monthlyTaxData as $data) {
            $index = $data->month - 1;
            $salesTaxData[$index] = (float) $data->totalSalesTax;
            $furtherTaxData[$index] = (float) $data->totalFurtherTax;
            $extraTaxData[$index] = (float) $data->totalExtraTax;
        }


        // Month-wise Invoice Details
        $monthlyInvoiceStatusData = Invoice::selectRaw('
            MONTH(invoice_date) as month,
            SUM(CASE WHEN invoice_status = ? THEN 1 ELSE 0 END) as draft_count,
            SUM(CASE WHEN invoice_status = ? THEN 1 ELSE 0 END) as posted_count
        ', [Invoice::STATUS_DRAFT, Invoice::STATUS_POSTED])
            ->whereYear('invoice_date', Carbon::now()->year)
            ->where('fbr_env', $fbrEnv)
            ->groupByRaw('MONTH(invoice_date)')
            ->orderByRaw('MONTH(invoice_date)')
            ->limit(12)
            ->get();
        $monthlyDraftCounts = array_fill(0, 12, 0);
        $monthlyPostedCounts = array_fill(0, 12, 0);
        foreach ($monthlyInvoiceStatusData as $data) {
            $index = $data->month - 1;
            $monthlyDraftCounts[$index] = (int) $data->draft_count;
            $monthlyPostedCounts[$index] = (int) $data->posted_count;
        }




        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('M');
        });
        $monthlyInvoicesCreated = Invoice::selectRaw('MONTH(invoice_date) as month, COUNT(*) as total')
            ->where('fbr_env', $fbrEnv)
            ->groupByRaw('MONTH(invoice_date)')
            ->pluck('total', 'month');
        $monthlyInvoicesFbrPosted = Invoice::selectRaw('MONTH(invoice_date) as month, COUNT(*) as total')
            ->where('fbr_env', $fbrEnv)
            ->where('is_posted_to_fbr', 1)
            ->groupByRaw('MONTH(invoice_date)')
            ->pluck('total', 'month');
        $createdData = [];
        $fbrData = [];
        for ($m = 1; $m <= 12; $m++) {
            $createdData[] = (int) ($monthlyInvoicesCreated[$m] ?? 0);
            $fbrData[]     = (int) ($monthlyInvoicesFbrPosted[$m] ?? 0);
        }
        $invoiceMonthlyStats = [
            'months' => $months,
            'series' => [
                [
                    'name' => 'Total Invoices Created',
                    'data' => $createdData,
                ],
                [
                    'name' => 'FBR Posted Invoices',
                    'data' => $fbrData,
                ],
            ],
        ];

        return view('dashboard', compact(
            'totalClients',
            'totalInvoices',
            'fbrPostedInvoices',
            'fbrpostedPercentage',
            'draftInvoices',
            'draftPercentage',
            'salesTaxData',
            'furtherTaxData',
            'extraTaxData',
            'monthlyLabels',
            'monthlyDraftCounts',
            'monthlyPostedCounts',
            'topClientNames',
            'topClientTotals',
            'topClientPercentages',
            'topServiceNamesRevenue',
            'topServiceTotalsRevenue',
            'topServicePercentagesRevenue',
            'invoiceMonthlyStats',
        ));
    }
}
