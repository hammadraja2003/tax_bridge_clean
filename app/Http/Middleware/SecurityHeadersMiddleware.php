<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));

        // Share nonce with all views
        View::share('nonce', $nonce);

        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer');

        // ✅ Allow disabling CSP completely from .env
        if (!env('CSP_ENABLED', true)) {
            return $response;
        }

        // ✅ Local / Dev environment → Relax CSP for tools, base64 fonts, and uploads
        if (app()->environment('local')) {
            $appUrl = config('app.url');
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self' {$appUrl}; " .
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
                    "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
                    "img-src 'self' data: blob: {$appUrl}; " . // ✅ uses APP_URL
                    "frame-src 'self'; " .
                    "object-src 'none'; " .
                    "frame-ancestors 'self';"
            );
        } else {
            $appUrl = config('app.url');
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; " .
                    "script-src 'self' 'nonce-$nonce'; " .
                    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
                    "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
                    "img-src 'self' data: blob: {$appUrl}; " . // ✅ dynamic APP_URL
                    "frame-src 'self'; " .
                    "object-src 'none'; " .
                    "frame-ancestors 'self';"
            );
        }

        return $response;
    }
}
