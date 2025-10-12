<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your custom middleware alias here
        $middleware->alias([
            'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'set.tenant' => \App\Http\Middleware\SetTenant::class,
            'business.configured' => \App\Http\Middleware\EnsureBusinessConfigured::class,
            'secret.key' => \App\Http\Middleware\SecretKeyMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
return $app;