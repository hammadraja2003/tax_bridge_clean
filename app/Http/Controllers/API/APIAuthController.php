<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
class APIAuthController extends Controller
{
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return errorResponse('Invalid credentials', 401);
        }
        $user = Auth::user();
        // Set tenant
        if ($user->tenant_id) {
            app(\App\Services\TenantManager::class)->setTenant($user->tenant_id);
        }
        // If user has 2FA enabled → do not issue tokens yet. Return pending 2FA token.
        if ($user->twofa_enabled && $user->twofa_secret) {
            // Create a short-lived pending token — store user id in cache keyed by random token
            $pending = Str::random(64);
            // store for 5 minutes (adjust as needed)
            Cache::put('2fa:pending:' . $pending, $user->id, now()->addMinutes(5));
            // Optional: track attempt count per IP/user to avoid brute force (see recommendations below)
            return successResponse([
                '2fa_required' => true,
                'twofa_pending_token' => $pending,
                'message' => 'Two-factor authentication required. Use the pending token with your 6-digit code.'
            ], 200, '2FA required');
        }
        // ------------- No 2FA: proceed to create tokens (same as your existing logic) -------------
        // Delete only ACCESS tokens
        $user->tokens()->where('name', 'access')->delete();
        // Create ACCESS token (1 hour)
        $accessToken = $user->createToken('access');
        $accessTokenString = $accessToken->plainTextToken;
        $accessToken->accessToken->expires_at = now()->addMinutes(60);
        $accessToken->accessToken->save();
        // Create REFRESH token (30 days)
        $refreshToken = $user->createToken('refresh');
        $refreshTokenString = $refreshToken->plainTextToken;
        $refreshToken->accessToken->expires_at = now()->addDays(30);
        $refreshToken->accessToken->save();
        $data = [
            'access_token'  => $accessTokenString,
            'refresh_token' => $refreshTokenString,
            'token_type'    => 'Bearer',
            'expires_in'    => 3600, // 1h
            'user'          => $user,
        ];
        return successResponse($data, 200, "Login successful");
    }
    public function refreshToken(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return errorResponse('Unauthenticated', 401);
        }
        $tokenModel = $user->currentAccessToken();
        if (!$tokenModel || $tokenModel->name !== 'refresh') {
            return errorResponse('Invalid refresh token', 401);
        }
        if ($tokenModel->expires_at && $tokenModel->expires_at->isPast()) {
            $tokenModel->delete();
            return errorResponse('Refresh token expired', 401);
        }
        // Delete old ACCESS tokens
        $user->tokens()->where('name', 'access')->delete();
        // Issue new ACCESS token
        $newAccessToken = $user->createToken('access');
        $access = $newAccessToken->plainTextToken;
        $newAccessToken->accessToken->expires_at = now()->addMinutes(60);
        $newAccessToken->accessToken->save();
        return successResponse([
            'access_token' => $access,
            'token_type'   => 'Bearer',
            'expires_in'   => 3600
        ], 200, "Token refreshed");
    }
    public function apiLogout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // Revoke current token
            $user->currentAccessToken()->delete();
            return successResponse([], 200, 'Logout successful');
        }
        return errorResponse('No authenticated user', 401);
    }
}
