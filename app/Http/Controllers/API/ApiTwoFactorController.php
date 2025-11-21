<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
class ApiTwoFactorController extends Controller
{
    public function verifyDuringLogin(Request $request)
    {
        $request->validate([
            'twofa_pending_token' => 'required|string',
            'code' => 'required|digits:6'
        ]);
        $pendingKey = '2fa:pending:' . $request->twofa_pending_token;
        $userId = Cache::get($pendingKey);
        if (!$userId) {
            return errorResponse('Invalid or expired 2FA session', 401);
        }
        $user = User::find($userId);
        if (!$user || !$user->twofa_enabled || !$user->twofa_secret) {
            Cache::forget($pendingKey);
            return errorResponse('Invalid 2FA session', 401);
        }
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->twofa_secret, $request->code);
        // -------------------------------
        // Brute-force protection
        // -------------------------------
        $attemptKey = '2fa:attempt:' . $request->twofa_pending_token;
        $attempts = Cache::get($attemptKey, 0);
        if ($attempts >= 5) {
            Cache::forget($pendingKey);
            Cache::forget($attemptKey);
            return errorResponse('Too many attempts. Start login again.', 429);
        }
        if (!$valid) {
            Cache::put($attemptKey, $attempts + 1, now()->addMinutes(10));
            return errorResponse('Invalid code', 422);
        }
        // ✅ Code is valid → clear attempts & pending
        Cache::forget($attemptKey);
        Cache::forget($pendingKey);
        // -------------------------------
        // Issue tokens (same as apiLogin)
        // -------------------------------
        $user->tokens()->where('name', 'access')->delete();
        $accessToken = $user->createToken('access');
        $accessTokenString = $accessToken->plainTextToken;
        $accessToken->accessToken->expires_at = now()->addMinutes(60);
        $accessToken->accessToken->save();
        $refreshToken = $user->createToken('refresh');
        $refreshTokenString = $refreshToken->plainTextToken;
        $refreshToken->accessToken->expires_at = now()->addDays(30);
        $refreshToken->accessToken->save();
        $data = [
            'access_token'  => $accessTokenString,
            'refresh_token' => $refreshTokenString,
            'token_type'    => 'Bearer',
            'expires_in'    => 3600,
            'user'          => $user,
        ];
        return successResponse($data, 200, '2FA verified, login successful');
    }
    public function setup(Request $request)
    {
        $user = $request->user();
        $google2fa = new Google2FA();
        if (!$user->twofa_secret) {
            $user->twofa_secret = $google2fa->generateSecretKey();
            $user->save();
        }
        $otpauth = $google2fa->getQRCodeUrl(
            config('app.name', 'TaxBridgeInvoiceManagment'),
            $user->email,
            $user->twofa_secret
        );
        $qr = Builder::create()
            ->writer(new PngWriter())
            ->data($otpauth)
            ->size(250)
            ->build();
        // Convert to Base64
        $qrBase64 = base64_encode($qr->getString());
        return successResponse([
            'enabled' => (bool) $user->twofa_enabled,
            'secret' => $user->twofa_secret,
            'qr_base64' => 'data:image/png;base64,' . $qrBase64,
        ], 200);
    }
    /**
     * POST /api/2fa/enable
     * Protected: auth:sanctum
     * Body: { secret, code }
     */
    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|digits:6'
        ]);
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($request->secret, $request->code);
        if (!$valid) {
            return errorResponse('Invalid verification code', 422);
        }
        $user = $request->user();
        $user->twofa_secret = $request->secret;
        $user->twofa_enabled = true;
        $user->save();
        return successResponse(['message' => 'Two-factor authentication enabled.'], 200);
    }
    public function disable(Request $request)
    {
        $user = $request->user();
        $user->twofa_enabled = false;
        // Optionally clear secret if you want: $user->twofa_secret = null;
        $user->save();
        return successResponse(['message' => 'Two-factor authentication disabled.'], 200);
    }
}
