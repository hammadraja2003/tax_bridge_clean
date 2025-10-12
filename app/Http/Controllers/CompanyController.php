<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessConfiguration;
use Illuminate\Support\Facades\Auth;
use App\Models\SandboxScenario;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $busConfigId = session('bus_config_id');
        $config = null;
        $selectedScenarios = [];
        if ($busConfigId) {
            // Fetch specific business configuration by bus_config_id
            $config = BusinessConfiguration::with('scenarios')->find($busConfigId);
            if ($config) {
                // Calculate tampering
                $config->tampered = $config->generateHash() !== $config->hash;
                // Get already selected scenarios
                $selectedScenarios = $config->scenarios->pluck('scenario_id')->toArray();
            }
        }
        // Load all available scenarios from master DB
        $scenarios = SandboxScenario::all();
        // If no config, set a flash message
        if (!$config) {
            session()->flash('error', 'Please configure your business first.');
        }
        return view('company.configuration', compact('config', 'scenarios', 'selectedScenarios'));
    }
    public function storeOrUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
            $config = BusinessConfiguration::where('bus_config_id', $tenantId)->first();
            // Fallback if no tenant ID is set
            if (!$config) {
                $config = null;
            }
            $request->validate([
                'id_type' => 'required|in:NTN,CNIC',
                'bus_ntn_cnic' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->id_type === 'NTN' && !preg_match('/^[0-9]{7}$/', $value)) {
                            $fail('NTN must be exactly 7 digits.');
                        }
                        if ($request->id_type === 'CNIC' && !preg_match('/^[0-9]{13}$/', $value)) {
                            $fail('CNIC must be exactly 13 digits (without dashes).');
                        }
                    }
                ],
                'bus_name' => 'required|string|max:255',
                'bus_account_title' => 'nullable|string|max:255',
                'bus_account_number' => 'nullable|string|max:255',
                'bus_reg_num' => 'nullable|string|max:255',
                'bus_contact_num' => 'nullable|string|max:20',
                'bus_contact_person' => 'nullable|string|max:255',
                'bus_IBAN' => 'nullable|string|max:255',
                'bus_acc_branch_name' => 'nullable|string|max:255',
                'bus_acc_branch_code' => 'nullable|string|max:255',
                'bus_logo' => $config && $config->bus_logo
                    ? 'nullable|mimes:jpg,jpeg,png,svg|max:2048'
                    : 'required|mimes:jpg,jpeg,png,svg|max:2048',
                'db_host' => 'required|string|max:255',
                'db_name' => 'required|string|max:255',
                'db_username' => 'required|string|max:255',
                'db_password' => 'required|string|max:255',
                'fbr_env' => 'required|in:sandbox,production',
                'fbr_api_token_sandbox' => 'nullable|string',
                'fbr_api_token_prod' => 'nullable|string',
                // 🔹 validate scenario_ids array
                'scenario_ids'   => 'nullable|array',
                'scenario_ids.*' => 'exists:sandbox_scenarios,scenario_id'
            ]);
            $data = $request->all();
            // Upload logo if provided

            // if ($request->hasFile('bus_logo')) {
            //     $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
            //     $file = $request->file('bus_logo');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;
            //     $folder = 'company';

            //     // Set visibility for S3, no effect on local
            //     $options = ($disk === 's3') ? ['visibility' => 'public'] : [];

            //     // Save file to the selected disk
            //     $path = Storage::disk($disk)->putFileAs($folder, $file, $filename, $options);

            //     // Store relative path (e.g. 'company/12345.png') in DB
            //     $data['bus_logo'] = $path;
            // } else {
            //     // Keep existing logo if no new one uploaded
            //     $data['bus_logo'] = $company->bus_logo ?? '';
            // }


            // if ($request->hasFile('bus_logo')) {
            //     $disk   = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
            //     $folder = 'company';

            //     // ✅ Delete old file if it exists
            //     if (!empty($company->bus_logo) && Storage::disk($disk)->exists($company->bus_logo)) {
            //         Storage::disk($disk)->delete($company->bus_logo);
            //     }

            //     $file      = $request->file('bus_logo');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename  = time() . '.' . $extension;

            //     // ⚡ Use working method (no visibility override for S3)
            //     $path = Storage::disk($disk)->putFileAs($folder, $file, $filename);

            //     if ($path) {
            //         // ✅ Save relative path (e.g. company/12345.png)
            //         $data['bus_logo'] = $path;
            //     } else {
            //         // ❌ Upload failed → keep old one
            //         $data['bus_logo'] = $company->bus_logo ?? '';
            //         Log::error('❌ Company logo update failed', [
            //             'disk'     => $disk,
            //             'folder'   => $folder,
            //             'filename' => $filename,
            //         ]);
            //     }
            // } else {
            //     // ⚡ Keep existing logo if no new one uploaded
            //     $data['bus_logo'] = $company->bus_logo ?? '';
            // }

            if ($request->hasFile('bus_logo')) {
                $disk   = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
                $folder = 'company';

                // ✅ Delete old file if it exists
                if (!empty($config->bus_logo) && Storage::disk($disk)->exists($config->bus_logo)) {
                    Storage::disk($disk)->delete($config->bus_logo);
                }

                $file      = $request->file('bus_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;

                // Upload file
                $path = Storage::disk($disk)->putFileAs($folder, $file, $filename);

                // ✅ Store new path
                $data['bus_logo'] = $path;
            } else {
                // ✅ Keep existing logo if no new one uploaded
                $data['bus_logo'] = $config->bus_logo ?? '';
            }


            if ($config) {
                $oldData = $config->toArray();
                $config->update($data);
                // ✅ Sync scenarios
                if ($request->has('scenario_ids')) {
                    $config->scenarios()->sync($request->scenario_ids);
                } else {
                    $config->scenarios()->detach();
                }
                $msg = 'Company configuration updated.';
            } else {
                $config = BusinessConfiguration::create($data);
                // ✅ Attach scenarios
                if ($request->has('scenario_ids')) {
                    $config->scenarios()->sync($request->scenario_ids);
                }
                $msg = 'Company configuration saved.';
            }
            config([
                'database.connections.tenant.host'     => $config->db_host,
                'database.connections.tenant.database' => $config->db_name,
                'database.connections.tenant.username' => $config->db_username,
                'database.connections.tenant.password' => $config->db_password,
            ]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            logActivity(
                $config->wasRecentlyCreated ? 'add' : 'update',
                $config->wasRecentlyCreated ? 'Added new company configuration' : 'Updated company configuration',
                $config->wasRecentlyCreated ? $config->toArray() : ['old' => $oldData, 'new' => $config->toArray()],
                $config->id,
                'business_configurations'
            );
            DB::commit();
            return redirect()->route('company.configuration')->with('success', $msg);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'db_error' => 'Something went wrong while saving company data. (' . $e->getMessage() . ')'
            ]);
        }
    }
}
