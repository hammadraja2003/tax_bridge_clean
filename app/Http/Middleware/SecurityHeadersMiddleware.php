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
        View::share('nonce', $nonce);
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        
        if (!env('CSP_ENABLED', true)) {
            return $response;
        }
        $appUrl = config('app.url');
        if (app()->environment('local')) {
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self' {$appUrl}; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://analytics.google.com https://browser.sentry-cdn.com https://js.sentry-cdn.com https://www.google.com https://www.gstatic.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
                "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
                "img-src 'self' data: blob: {$appUrl} https://*.s3.amazonaws.com https://*.s3." . env('AWS_DEFAULT_REGION', '*') . ".amazonaws.com https://www.google.com https://www.gstatic.com; " .
                "connect-src 'self' https://www.google-analytics.com https://region1.google-analytics.com https://sentry.io https://*.ingest.sentry.io;" .
                "frame-src 'self' https://www.google.com https://www.gstatic.com; " .
                "object-src 'none'; " .
                "frame-ancestors 'self';"
            );
        } else {
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; " .
                "script-src 'self' 'nonce-$nonce' https://www.googletagmanager.com https://www.google-analytics.com https://analytics.google.com https://browser.sentry-cdn.com https://js.sentry-cdn.com https://www.google.com https://www.gstatic.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
                "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
                "img-src 'self' data: blob: {$appUrl} https://*.s3.amazonaws.com https://*.s3." . env('AWS_DEFAULT_REGION', '*') . ".amazonaws.com https://www.google.com https://www.gstatic.com; " .
                "connect-src 'self' https://www.google-analytics.com https://region1.google-analytics.com https://sentry.io https://*.ingest.sentry.io;" .
                "frame-src 'self' https://www.google.com https://www.gstatic.com; " .
                "object-src 'none'; " .
                "frame-ancestors 'self';"
            );
        }
        return $response;
    }
}
