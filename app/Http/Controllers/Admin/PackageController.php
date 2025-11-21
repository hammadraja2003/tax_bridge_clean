<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Support\Facades\DB;
class PackageController extends Controller
{
    // ---------------------------
    // List all packages
    // ---------------------------
    public function index()
    {
        $packages = Package::paginate(10);
        return view('admin.packages.index', compact('packages'));
    }
    // ---------------------------
    // Show form to create package
    // ---------------------------
    public function create()
    {
        return view('admin.packages.create');
    }
    // ---------------------------
    // Store new package
    // ---------------------------
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_description' => 'nullable|string',
            'package_price' => 'required|numeric|min:0',
            'package_billing_cycle' => 'required|in:monthly,quarterly,yearly,custom',
            'features.*.feature_key' => 'required|string|max:100',
            'features.*.limit_type' => 'required|in:monthly,quarterly,yearly,total',
            'features.*.limit_value' => 'required|integer|min:0',
        ]);
        DB::transaction(function () use ($request) {
            $package = Package::create([
                'package_name' => $request->package_name,
                'package_description' => $request->package_description,
                'package_price' => $request->package_price,
                'package_billing_cycle' => $request->package_billing_cycle,
            ]);
            if ($request->has('features')) {
                foreach ($request->features as $feature) {
                    PackageFeature::create([
                        'package_id' => $package->package_id,
                        'feature_key' => $feature['feature_key'],
                        'limit_type' => $feature['limit_type'],
                        'limit_value' => $feature['limit_value'],
                    ]);
                }
            }
        });
        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }
    // ---------------------------
    // Show package edit form
    // ---------------------------
    public function edit($id)
    {
        $package = Package::with('features')->findOrFail($id);
        return view('admin.packages.edit', compact('package'));
    }
    // ---------------------------
    // Update package
    // ---------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_description' => 'nullable|string',
            'package_price' => 'required|numeric|min:0',
            'package_billing_cycle' => 'required|in:monthly,quarterly,yearly,custom',
            'features.*.feature_key' => 'required|string|max:100',
            'features.*.limit_type' => 'required|in:monthly,quarterly,yearly,total',
            'features.*.limit_value' => 'required|integer|min:0',
        ]);
        DB::transaction(function () use ($request, $id) {
            $package = Package::findOrFail($id);
            $package->update([
                'package_name' => $request->package_name,
                'package_description' => $request->package_description,
                'package_price' => $request->package_price,
                'package_billing_cycle' => $request->package_billing_cycle,
            ]);
            // Delete old features and insert new
            $package->features()->delete();
            if ($request->has('features')) {
                foreach ($request->features as $feature) {
                    PackageFeature::create([
                        'package_id' => $package->package_id,
                        'feature_key' => $feature['feature_key'],
                        'limit_type' => $feature['limit_type'],
                        'limit_value' => $feature['limit_value'],
                    ]);
                }
            }
        });
        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }
    // ---------------------------
    // Delete package
    // ---------------------------
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }
}
