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

        // Common security headers
        // $response->headers->set('X-Frame-Options', 'DENY');
        // $response->headers->set('X-Content-Type-Options', 'nosniff');
        // $response->headers->set('Referrer-Policy', 'no-referrer');

        // ✅ Allow disabling CSP completely from .env
        if (!env('CSP_ENABLED', true)) {
            return $response;
        }

        // ✅ Local / Dev environment → Relax CSP for tools, base64 fonts, and uploads
        // if (app()->environment('local')) {
        //     $response->headers->set(
        //         'Content-Security-Policy',
        //         "default-src 'self' http://127.0.0.1:8000; " .
        //         "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
        //         "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
        //         "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
        //         "img-src 'self' data: blob: http://127.0.0.1:8000; " .
        //         "object-src 'none'; " .
        //         "frame-ancestors 'none';"
        //     );
        // } else {
        //     // ✅ Production → Secure but still allows uploads & fonts
        //     $response->headers->set(
        //         'Content-Security-Policy',
        //         "default-src 'self'; " .
        //         "script-src 'self' 'nonce-$nonce'; " .
        //         "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
        //         "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
        //         "img-src 'self' data: blob: https://yourdomain.com; " .
        //         "object-src 'none'; " .
        //         "frame-ancestors 'none';"
        //     );
        // }

        return $response;
    }
}
