<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\SandboxScenario;
use App\Models\BusinessConfiguration;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $scenarios = SandboxScenario::all();
        return view('auth.register', compact('scenarios'));
    }
    public function store(Request $request)
    {
        $request->validate([
            // Company Details
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
            // keep bus_reg_num optional in validation
            'bus_contact_num' => 'required|string|max:20',
            'bus_contact_person' => 'required|string|max:255',
            'bus_province' => 'required|string|max:50',
            'bus_address' => 'required|string|max:500',
            'bus_logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            // FBR / Config
            'fbr_env' => 'required|in:sandbox,production',
            'fbr_api_token_sandbox' => 'required|string',
            'fbr_api_token_prod' => 'nullable|string',
            'scenario_ids' => 'required|array',

            // User
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            // grab only the keys we intend to persist
            $data = $request->only([
                'bus_name',
                'bus_ntn_cnic',
                'bus_reg_num',
                'bus_contact_num',
                'bus_contact_person',
                'bus_province',
                'bus_address',
                'bus_acc_branch_name',
                'bus_acc_branch_code',
                'bus_account_title',
                'bus_account_number',
                'bus_IBAN',
                'bus_swift_code',
                'fbr_env',
                'fbr_api_token_sandbox',
                'fbr_api_token_prod'
            ]);

            $defaults = [
                'bus_reg_num'         => 'N/A',
                'bus_acc_branch_name' => 'N/A',
                'bus_acc_branch_code' => 'N/A',
                'bus_account_title'   => 'N/A',
                'bus_account_number'  => 'N/A',
                'bus_IBAN'            => 'N/A',
                'bus_swift_code'      => 'N/A',
            ];

            foreach ($defaults as $key => $value) {
                if (!isset($data[$key]) || $data[$key] === null || $data[$key] === '') {
                    $data[$key] = $value;
                }
            }

            if ($request->hasFile('bus_logo')) {
                $disk   = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
                $folder = 'company';

                $file      = $request->file('bus_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;

                $path = Storage::disk($disk)->putFileAs($folder, $file, $filename);
                $data['bus_logo'] = $path;
            } else {
                // safe default if somehow missing (your validation requires it)
                $data['bus_logo'] = $data['bus_logo'] ?? '';
            }

            // DB credentials & generated DB name
            $data['db_host'] = '127.0.0.1';
            $data['db_username'] = 'root';
            $data['db_password'] = 'Admin';

            $cleanBusName = strtolower($request->bus_name);
            $cleanBusName = preg_replace('/[^a-z0-9]+/i', '_', $cleanBusName);
            $cleanBusName = trim($cleanBusName, '_');
            $cleanBusName = preg_replace('/_+/', '_', $cleanBusName);

            $uniqueSuffix = substr(sha1(uniqid(mt_rand(), true)), 0, 6);
            $data['db_name'] = 'fbr_' . $cleanBusName . '_' . $uniqueSuffix . '_db';

            // Create business configuration (this will now always have bus_reg_num non-null)
            $business = BusinessConfiguration::create($data);
            $busConfigId = $business->bus_config_id;

            // Insert scenarios (many-to-many)
            if (!empty($request->scenario_ids)) {
                foreach ($request->scenario_ids as $scenarioId) {
                    DB::table('business_scenarios')->insert([
                        'bus_config_id' => $busConfigId,
                        'scenario_id'   => $scenarioId,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'tenant_id' => $busConfigId,
                'email' => $request->email ?? null,
            ]);

            event(new Registered($user));

            DB::commit();

            return redirect()->route('login')->with('success', 'Configuration saved successfully! Please log in to continue.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }
}
