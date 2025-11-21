<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Dotenv\Dotenv;

$basePath = dirname(__DIR__);
$host = $_SERVER['HTTP_HOST'] ?? '';
$envFile = '.env'; // Default
switch (true) {
    case str_contains($host, 'localhost'):
    case str_contains($host, '127.0.0.1'):
        $envFile = '.env.local';
        break;
    case str_contains($host, 'app.taxbridge.pk'):
        $envFile = '.env';
        break;
    default:
        $envFile = '.env.local';
        break;
}
Dotenv::createImmutable($basePath, $envFile)->safeLoad();
$app = Application::configure(basePath: $basePath)
    ->withRouting(
        using: function () {
            require __DIR__ . '/../routes/web.php';
            if (file_exists(__DIR__ . '/../routes/auth.php')) {
                require __DIR__ . '/../routes/auth.php';
            }
            if (file_exists(__DIR__ . '/../routes/admin.php')) {
                require __DIR__ . '/../routes/admin.php';
            }
            if (file_exists(__DIR__ . '/../routes/api.php')) {
                require __DIR__ . '/../routes/api.php';
            }
        },
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'security.headers'   => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'set.tenant'         => \App\Http\Middleware\SetTenant::class,
            'business.configured' => \App\Http\Middleware\EnsureBusinessConfigured::class,
            'admin.auth' => \App\Http\Middleware\RedirectIfNotAdmin::class,
            'tenant.api' => \App\Http\Middleware\SetTenantForApi::class,
            'check.token.expire' => \App\Http\Middleware\CheckTokenExpire::class,
        ]);
    })
    // ->withExceptions(function (Exceptions $exceptions) {
    //     //
    // })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'code'    => 401,
                        'message' => 'Unauthenticated',
                    ], 401);
                }
                return redirect()->guest(route('login'));
            }
            return null;
        });
    })
    ->create();
return $app;
