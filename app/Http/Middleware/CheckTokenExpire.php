<?php
namespace App\Http\Middleware;
use Closure;
class CheckTokenExpire
{
    public function handle($request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token && $token->expires_at && $token->expires_at->isPast()) {
            $token->delete();
            return errorResponse('Token expired', 401);
        }
        return $next($request);
    }
}
