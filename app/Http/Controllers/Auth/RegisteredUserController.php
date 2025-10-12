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
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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
            'bus_reg_num' => 'required|string|max:50',
            'bus_contact_num' => 'required|string|max:20',
            'bus_contact_person' => 'required|string|max:255',
            'bus_province' => 'required|string|max:50',
            'bus_address' => 'required|string|max:500',
            'bus_logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            // Banking Details
            'bus_acc_branch_name'   => 'required|string|max:255',
            'bus_acc_branch_code'   => 'required|string|max:50',
            'bus_account_title'     => 'required|string|max:255',
            'bus_account_number'    => 'required|string|max:50',
            'bus_IBAN'              => 'required|string|max:34',
            'bus_swift_code'        => 'nullable|string|max:50',
            // FBR / Config
            'fbr_env' => 'required|in:sandbox,production',
            'fbr_api_token_sandbox' => 'required|string',
            'fbr_api_token_prod' => 'nullable|string',
            'scenario_ids' => 'required|array',
            // User
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6', // removed "confirmed"
        ]);
        DB::beginTransaction();
        try {
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
            // Handle logo upload
            // if ($request->hasFile('bus_logo')) {
            //     $file = $request->file('bus_logo');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;
            //     $file->move(public_path('uploads/company'), $filename);
            //     $data['bus_logo'] = $filename;
            // }
            if ($request->hasFile('bus_logo')) {
                $file = $request->file('bus_logo');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;

                $path = public_path('uploads/company');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }

                $file->move($path, $filename);

                $data['bus_logo'] = $filename;
            }

            // DB credentials
            $data['db_host'] = '127.0.0.1';
            $data['db_username'] = 'root';
            $data['db_password'] = 'Admin';
            $cleanBusName = strtolower($request->bus_name);
            $cleanBusName = preg_replace('/[^a-z0-9]+/i', '_', $cleanBusName);
            $cleanBusName = trim($cleanBusName, '_');
            $cleanBusName = preg_replace('/_+/', '_', $cleanBusName);
            // Unique suffix (timestamp + random string for safety)
            $uniqueSuffix = substr(sha1(uniqid(mt_rand(), true)), 0, 6);
            // Add fixed prefix "fbr"
            $data['db_name'] = 'fbr_' . $cleanBusName . '_' . $uniqueSuffix . '_db';
            // Insert into business_configurations
            $business = BusinessConfiguration::create($data);
            $busConfigId = $business->bus_config_id;
            // Insert scenarios (many to many)
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
            // Insert user
            $user = User::create([
                'name' => $request->name,
                'password' => !empty($request->password) ? Hash::make($request->password) : Hash::make('123456'),
                'tenant_id' => $busConfigId,
                'email' => $request->email ?? null,
            ]);
            // Fire registered event (optional)
            event(new Registered($user));
            DB::commit();
            return redirect()->route('login')->with('success', 'Configuration saved successfully! Please log in to continue.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }
}