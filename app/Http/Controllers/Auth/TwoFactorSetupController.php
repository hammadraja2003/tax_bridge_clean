<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
class TwoFactorSetupController extends Controller
{
    // Show the 2FA setup page
    public function showSetupForm()
    {
       
        $user = Auth::user();
        $google2fa = new Google2FA();
        // If user has never set up 2FA, redirect to profile to start setup
        if (!$user->twofa_secret) {
            $google2fa = new Google2FA();
            $user->twofa_secret = $google2fa->generateSecretKey();
            $user->save();
        }
        // Use existing secret
        $secret = $user->twofa_secret;
        $busName = session('bus_name', 'TaxBridgeInvoiceManagment');
        $otpauth = $google2fa->getQRCodeUrl(
            $busName,
            $user->email,
            $secret
        );
        $qrSvg = QrCode::format('svg')->size(200)->generate($otpauth);
        return view('auth.2fa-setup', [
            'qrSvg' => $qrSvg,
            'secret' => $secret,
            'enabled' => (bool) $user->twofa_enabled,
        ]);
    }
    // Enable 2FA
    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code'   => 'required|digits:6',
        ]);
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($request->secret, $request->code);
        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid code. Make sure you scanned the QR and try again.']);
        }
        $user = Auth::user();
        // Set secret if not already set
        if (!$user->twofa_secret) {
            $user->twofa_secret = $request->secret;
        }
        $user->twofa_enabled = true;
        $user->save();
        // 🔹 Encrypt the user ID for the route
        $encryptedId = Crypt::encrypt($user->id);
        return redirect()->route('edit-profile', $encryptedId)
            ->with('status', 'Two-factor authentication enabled.');
    }
    // Disable 2FA
    public function disable()
    {
        $user = Auth::user();
        $user->twofa_enabled = false;
        $user->save();
        $encryptedId = Crypt::encrypt($user->id);
        return redirect()->route('edit-profile', $encryptedId)
            ->with('status', 'Two-factor authentication disabled.');
    }
}
