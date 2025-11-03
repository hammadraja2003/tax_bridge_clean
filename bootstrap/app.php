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
            // --- Standard web routes (middleware:web) ---
            require __DIR__ . '/../routes/web.php';

            // --- Auth routes (login/register/forgot etc) ---
            if (file_exists(__DIR__ . '/../routes/auth.php')) {
                require __DIR__ . '/../routes/auth.php';
            }

            // --- Admin routes ---
            if (file_exists(__DIR__ . '/../routes/admin.php')) {
                require __DIR__ . '/../routes/admin.php';
            }

            // --- API routes (optional, no web middleware) ---
            if (file_exists(__DIR__ . '/../routes/api.php')) {
                require __DIR__ . '/../routes/api.php';
            }

            // --- Console routes ---
            if (file_exists(__DIR__ . '/../routes/console.php')) {
                require __DIR__ . '/../routes/console.php';
            }
        },
        web: __DIR__ . '/../routes/web.php',  // default web route fallback
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )



    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'security.headers'   => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'set.tenant'         => \App\Http\Middleware\SetTenant::class,
            'business.configured' => \App\Http\Middleware\EnsureBusinessConfigured::class,
            // 'secret.key'         => \App\Http\Middleware\SecretKeyMiddleware::class,
            'admin.auth' => \App\Http\Middleware\RedirectIfNotAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
// ✅ If you need to know which .env file was used, just uncommetn below this:
// file_put_contents($basePath . '/storage/logs/env_boot.log', date('Y-m-d H:i:s') . " => Loaded {$envFile}\n", FILE_APPEND);
return $app;
