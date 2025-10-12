<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
class TwoFactorController extends Controller
{
    public function showVerifyForm()
    {
        // If there is no pending 2FA user in session, go back to login
        if (!session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }
        return view('auth.2fa-verify');
    }
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $userId = $request->session()->get('2fa:user:id');
        $user = User::find($userId);
        if (!$user || !$user->twofa_enabled || !$user->twofa_secret) {
            return redirect()->route('login')->withErrors(['email' => '2FA session expired. Please login again.']);
        }
        $google2fa = new Google2FA();
        // You can allow slight clock drift with a window (default 4), but basic verify is fine:
        $valid = $google2fa->verifyKey($user->twofa_secret, $request->code);
        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid code. Please try again.']);
        }
        // Success → log in fully
        Auth::login($user);
        $request->session()->forget('2fa:user:id');
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }
}