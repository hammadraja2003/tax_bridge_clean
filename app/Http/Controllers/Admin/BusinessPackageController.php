<?php
/*
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessConfiguration;
use App\Models\Package;
use App\Models\PackageFeature;
use App\Models\BusinessPackage;
use App\Models\BusinessPackageFeature;
use App\Models\BusinessFeatureUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use DB;
class BusinessPackageController extends Controller
{
    public function index()
    {
        $assigned = BusinessPackage::with([
            'business',
            'package',
            'features',
            'usage' => function ($q) {
                $q->where('period_end_date', '>=', now());
            }
        ])->orderBy('business_id')
            ->orderByDesc('is_active')
            ->get();
        return view('admin.packages.assigned_list', compact('assigned'));
    }
    public function showAssignForm()
    {
        $businesses = BusinessConfiguration::all();
        $packages = Package::all();
        return view('admin.packages.assign', compact('businesses', 'packages'));
    }
    public function assignPackage(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:business_configurations,bus_config_id',
            'package_id'  => 'required|exists:packages,package_id',
            'features'    => 'required|array',
            'features.*.feature_key' => 'required|string',
            'features.*.limit_type'  => 'required|string',
            'features.*.limit_value' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            $business = BusinessConfiguration::findOrFail($request->business_id);
            $package  = Package::findOrFail($request->package_id);
            // 🔥 Auto deactivate previously active package
            BusinessPackage::where('business_id', $request->business_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            switch ($package->package_billing_cycle) {
                case 'monthly':
                    $calculatedEndDate = now()->addMonth()->toDateString();
                    break;
                case 'quarterly':
                    $calculatedEndDate = now()->addMonths(3)->toDateString();
                    break;
                case 'yearly':
                    $calculatedEndDate = now()->addYear()->toDateString();
                    break;
                default:
                    $calculatedEndDate = now()->addMonth()->toDateString();
                    break;
            }
            $discount = $request->discount ?? 0;
            $finalPrice = $request->price_after_discount ?? $package->package_price;
            $isTrial = $request->boolean('is_trial');
            $trialDays = $isTrial ? (int) ($request->trial_days ?? 0) : 0;
            $startDate = now()->toDateString();
            $endDate = $isTrial ? now()->addDays($trialDays)->toDateString() : $calculatedEndDate;
            $businessPackage = BusinessPackage::create([
                'business_id' => $business->bus_config_id,
                'package_id' => $package->package_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'discount' => $isTrial ? 0 : ($request->discount ?? 0),
                'price_after_discount' => $isTrial ? 0 : ($request->price_after_discount ?? $package->package_price), // fixed key
                'is_active' => true,
                'is_trial' => $isTrial ? 1 : 0,
                'trial_end_date' => $isTrial ? $endDate : null,
            ]);
            foreach ($request->features as $f) {
                BusinessPackageFeature::create([
                    'business_package_id' => $businessPackage->business_packages_id,
                    'feature_key' => $f['feature_key'],
                    'limit_type' => $f['limit_type'],
                    'limit_value' => $f['limit_value'],
                ]);
                BusinessFeatureUsage::create([
                    'business_id' => $business->bus_config_id,
                    'business_package_id' => $businessPackage->business_packages_id,
                    'feature_key' => $f['feature_key'],
                    'period_start_date' => $startDate,
                    'period_end_date' => $endDate,
                    'used_count' => 0,
                ]);
            }
            DB::commit();
            return redirect()->route('admin.business_packages.index')
                ->with('success', 'Package assigned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['toast_error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    public function renew(Request $request)
    {
        $id = Crypt::decryptString($request->business_packages_id);
        $oldPackage = BusinessPackage::with('packageFeatures')->findOrFail($id);
        // Deactivate old package
        $oldPackage->update(['is_active' => false]);
        $package = $oldPackage->package;
        $startDate = now()->toDateString();
        // Determine end date
        $endDate = match ($package->package_billing_cycle) {
            'monthly'   => now()->addMonth()->toDateString(),
            'quarterly' => now()->addMonths(3)->toDateString(),
            'yearly'    => now()->addYear()->toDateString(),
            default     => now()->addMonth()->toDateString(),
        };
        DB::beginTransaction();
        try {
            // Create new business package
            $newPackage = BusinessPackage::create([
                'business_id'          => $oldPackage->business_id,
                'package_id'           => $package->package_id,
                'start_date'           => $startDate,
                'end_date'             => $endDate,
                'discount'             => $oldPackage->discount,
                'price_after_discount' => $oldPackage->price_after_discount,
                'is_active'            => true,
                'is_trial'             => 0,
                'trial_end_date'       => null,
            ]);
            // Copy features + create fresh usage rows
            foreach ($oldPackage->packageFeatures as $feature) {
                // Insert new package feature row
                BusinessPackageFeature::create([
                    'business_package_id' => $newPackage->business_packages_id,
                    'feature_key'         => $feature->feature_key,
                    'limit_type'          => $feature->limit_type,
                    'limit_value'         => $feature->limit_value,
                ]);
                // Insert new usage row
                BusinessFeatureUsage::create([
                    'business_id'          => $oldPackage->business_id,
                    'business_package_id'  => $newPackage->business_packages_id,
                    'feature_key'          => $feature->feature_key,
                    'period_start_date'    => $startDate,
                    'period_end_date'      => $endDate,
                    'used_count'           => 0,
                ]);
            }
            DB::commit();
            return back()->with('message', 'Package renewed successfully!');
        } catch (\Exception $e) {
            Log::info(['error' => $e->getMessage()]);
            DB::rollBack();
            return back()->withErrors(['toast_error' => $e->getMessage()]);
        }
    }
    public function toggleActive(Request $request)
    {
        $request->validate([
            'business_packages_id' => 'required|string',
        ]);
        $id = Crypt::decryptString($request->business_packages_id);
        $package = BusinessPackage::findOrFail($id);
        // If currently inactive, trying to activate
        if (!$package->is_active) {
            // Check if another active package exists for this business
            $activePackageExists = BusinessPackage::where('business_id', $package->business_id)
                ->where('is_active', true)
                ->exists();
            if ($activePackageExists) {
                return redirect()->back()->with('message', 'Another active package already exists for this business.');
            }
            // No other active package, activate this one
            $package->update(['is_active' => true]);
            return redirect()->back()->with('message', 'Business package has been activated.');
        }
        // If currently active, deactivate it
        $package->update(['is_active' => false]);
        return redirect()->back()->with('message', 'Business package has been deactivated.');
    }
}
*/
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessConfiguration;
use App\Models\Package;
use App\Models\PackageFeature;
use App\Models\BusinessPackage;
use App\Models\BusinessPackageFeature;
use App\Models\BusinessFeatureUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use DB;
class BusinessPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = BusinessPackage::with([
            'business',
            'package',
            'features',
            'usage' => function ($q) {
                $q->where('period_end_date', '>=', now());
            }
        ]);
        // Filter by Business
        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }
        // Filter by Package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }
        // Filter by Status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', 1)
                          ->where('end_date', '>=', now());
                    break;
                case 'inactive':
                    $query->where('is_active', 0);
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'trial':
                    $query->where('is_trial', 1);
                    break;
                case 'trial_active':
                    $query->where('is_trial', 1)
                          ->where('trial_end_date', '>=', now());
                    break;
                case 'trial_expired':
                    $query->where('is_trial', 1)
                          ->where('trial_end_date', '<', now());
                    break;
            }
        }
        // Filter by Start Date Range
        if ($request->filled('start_date_from')) {
            $query->where('start_date', '>=', $request->start_date_from);
        }
        if ($request->filled('start_date_to')) {
            $query->where('start_date', '<=', $request->start_date_to);
        }
        // Filter by End Date Range
        if ($request->filled('end_date_from')) {
            $query->where('end_date', '>=', $request->end_date_from);
        }
        if ($request->filled('end_date_to')) {
            $query->where('end_date', '<=', $request->end_date_to);
        }
        // Filter by Expiring Soon (e.g., within 7 or 30 days)
        if ($request->filled('expiring_soon')) {
            $days = (int) $request->expiring_soon;
            $query->where('end_date', '>=', now())
                  ->where('end_date', '<=', now()->addDays($days))
                  ->where('is_active', 1);
        }
        // Filter by Discount Range
        if ($request->filled('discount_min')) {
            $query->where('discount', '>=', $request->discount_min);
        }
        if ($request->filled('discount_max')) {
            $query->where('discount', '<=', $request->discount_max);
        }
        // Filter by Price Range
        if ($request->filled('price_min')) {
            $query->where('price_after_discout', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price_after_discout', '<=', $request->price_max);
        }
        $assigned = $query->orderBy('business_id')
            ->orderByDesc('is_active')
            ->get();
        // Get data for filter dropdowns
        $businesses = BusinessConfiguration::all();
        $packages = Package::all();
        return view('admin.packages.assigned_list', compact('assigned', 'businesses', 'packages'));
    }
    /**
     * Show assign package form
     */
    public function showAssignForm()
    {
        $businesses = BusinessConfiguration::all();
        $packages = Package::all();
        return view('admin.packages.assign', compact('businesses', 'packages'));
    }
    /**
     * Assign package to business and snapshot features
     */
    public function assignPackage(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:business_configurations,bus_config_id',
            'package_id'  => 'required|exists:packages,package_id',
            'features'    => 'required|array',
            'features.*.feature_key' => 'required|string',
            'features.*.limit_type'  => 'required|string',
            'features.*.limit_value' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            $business = BusinessConfiguration::findOrFail($request->business_id);
            $package  = Package::findOrFail($request->package_id);
            // 🔥 Auto deactivate previously active package
            BusinessPackage::where('business_id', $request->business_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            switch ($package->package_billing_cycle) {
                case 'monthly':
                    $calculatedEndDate = now()->addMonth()->toDateString();
                    break;
                case 'quarterly':
                    $calculatedEndDate = now()->addMonths(3)->toDateString();
                    break;
                case 'yearly':
                    $calculatedEndDate = now()->addYear()->toDateString();
                    break;
                default:
                    $calculatedEndDate = now()->addMonth()->toDateString();
                    break;
            }
            $discount = $request->discount ?? 0;
            $finalPrice = $request->price_after_discount ?? $package->package_price;
            $isTrial = $request->boolean('is_trial');
            $trialDays = $isTrial ? (int) ($request->trial_days ?? 0) : 0;
            $startDate = now()->toDateString();
            $endDate = $isTrial ? now()->addDays($trialDays)->toDateString() : $calculatedEndDate;
            $businessPackage = BusinessPackage::create([
                'business_id' => $business->bus_config_id,
                'package_id' => $package->package_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'discount' => $isTrial ? 0 : ($request->discount ?? 0),
                'price_after_discount' => $isTrial ? 0 : ($request->price_after_discount ?? $package->package_price),
                'is_active' => true,
                'is_trial' => $isTrial ? 1 : 0,
                'trial_end_date' => $isTrial ? $endDate : null,
            ]);
            foreach ($request->features as $f) {
                BusinessPackageFeature::create([
                    'business_package_id' => $businessPackage->business_packages_id,
                    'feature_key' => $f['feature_key'],
                    'limit_type' => $f['limit_type'],
                    'limit_value' => $f['limit_value'],
                ]);
                BusinessFeatureUsage::create([
                    'business_id' => $business->bus_config_id,
                    'business_package_id' => $businessPackage->business_packages_id,
                    'feature_key' => $f['feature_key'],
                    'period_start_date' => $startDate,
                    'period_end_date' => $endDate,
                    'used_count' => 0,
                ]);
            }
            DB::commit();
            return redirect()->route('admin.business_packages.index')
                ->with('success', 'Package assigned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['toast_error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    public function renew(Request $request)
    {
        $id = Crypt::decryptString($request->business_packages_id);
        $oldPackage = BusinessPackage::with('packageFeatures')->findOrFail($id);
        // Deactivate old package
        $oldPackage->update(['is_active' => false]);
        $package = $oldPackage->package;
        $startDate = now()->toDateString();
        // Determine end date
        $endDate = match ($package->package_billing_cycle) {
            'monthly'   => now()->addMonth()->toDateString(),
            'quarterly' => now()->addMonths(3)->toDateString(),
            'yearly'    => now()->addYear()->toDateString(),
            default     => now()->addMonth()->toDateString(),
        };
        DB::beginTransaction();
        try {
            // Create new business package
            $newPackage = BusinessPackage::create([
                'business_id'          => $oldPackage->business_id,
                'package_id'           => $package->package_id,
                'start_date'           => $startDate,
                'end_date'             => $endDate,
                'discount'             => $oldPackage->discount,
                'price_after_discount' => $oldPackage->price_after_discount,
                'is_active'            => true,
                'is_trial'             => 0,
                'trial_end_date'       => null,
            ]);
            // Copy features + create fresh usage rows
            foreach ($oldPackage->packageFeatures as $feature) {
                // Insert new package feature row
                BusinessPackageFeature::create([
                    'business_package_id' => $newPackage->business_packages_id,
                    'feature_key'         => $feature->feature_key,
                    'limit_type'          => $feature->limit_type,
                    'limit_value'         => $feature->limit_value,
                ]);
                // Insert new usage row
                BusinessFeatureUsage::create([
                    'business_id'          => $oldPackage->business_id,
                    'business_package_id'  => $newPackage->business_packages_id,
                    'feature_key'          => $feature->feature_key,
                    'period_start_date'    => $startDate,
                    'period_end_date'      => $endDate,
                    'used_count'           => 0,
                ]);
            }
            DB::commit();
            return back()->with('message', 'Package renewed successfully!');
        } catch (\Exception $e) {
            Log::info(['error' => $e->getMessage()]);
            DB::rollBack();
            return back()->withErrors(['toast_error' => $e->getMessage()]);
        }
    }
    public function toggleActive(Request $request)
    {
        $request->validate([
            'business_packages_id' => 'required|string',
        ]);
        $id = Crypt::decryptString($request->business_packages_id);
        $package = BusinessPackage::findOrFail($id);
        // If currently inactive, trying to activate
        if (!$package->is_active) {
            // Check if another active package exists for this business
            $activePackageExists = BusinessPackage::where('business_id', $package->business_id)
                ->where('is_active', true)
                ->exists();
            if ($activePackageExists) {
                return redirect()->back()->with('message', 'Another active package already exists for this business.');
            }
            // No other active package, activate this one
            $package->update(['is_active' => true]);
            return redirect()->back()->with('message', 'Business package has been activated.');
        }
        // If currently active, deactivate it
        $package->update(['is_active' => false]);
        return redirect()->back()->with('message', 'Business package has been deactivated.');
    }
}
