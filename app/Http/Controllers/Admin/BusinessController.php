<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBusiness;
use Illuminate\Support\Facades\Crypt;

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
            abort(404, 'Invalid ID');
        }

        $business = AdminBusiness::with(['users', 'scenarios'])->findOrFail($id);

        return view('admin.businesses.show', compact('business'));
    }
}
