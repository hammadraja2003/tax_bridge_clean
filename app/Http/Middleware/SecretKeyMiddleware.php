<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class SecretKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If already validated, proceed
        if (session('secret_validated')) {
            return $next($request);
        }
        // If request has secret param & it's correct
        if ($request->has('secret') && $request->secret === config('app.db_secret')) {
            session(['secret_validated' => true]);
            return redirect($request->url()); // reload without secret in URL
        }
        // Otherwise show a JS prompt
        return response("
            <script>
                var key = prompt('Enter Secret Key to access this page:');
                if (key) {
                    window.location.href = '?secret=' + encodeURIComponent(key);
                } else {
                    window.location.href = '/'; // redirect to home if cancelled
                }
            </script>
        ");
    }
}
