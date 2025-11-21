<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminBusiness;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class BusinessController extends Controller
{
    public function index()
    {
        $businesses = AdminBusiness::withCount(['users', 'scenarios'])
            ->orderBy('bus_name')
            ->paginate(15);
        return view('admin.businesses.index', compact('businesses'));
    }
    public function show($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
             return back()
                ->withInput()
                ->withErrors(['toast_error' =>  'Invalid ID']);
        }
        $business = AdminBusiness::with(['users', 'scenarios'])->findOrFail($id);
        return view('admin.businesses.show', compact('business'));
    }
    public function createUser($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);            
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
             return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        }
        return view('admin.businesses.createbusinessuser', compact('id'));
    }
    public function registerUser(Request $request)
    {
         $request->validate([
            // User
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6',
        ]);
        try {
            $busConfigId = $request->id;
             // Create user
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'tenant_id' => $busConfigId,
                'email' => $request->email ?? null,
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
             return back()
                ->withInput()
                ->withErrors(['toast_error' =>  $e->getMessage()]);
        }
          return redirect()->route('admin.businesses.index')
                ->with('message', 'User added successfully.');
    }
}
