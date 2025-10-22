<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            // Public API routes (no authentication required)
            Route::prefix('api/v1')
                ->name('api.')
                ->group(base_path('routes/api/api.php'));

            // Protected API routes (authentication required)
            Route::prefix('api/v1')
                ->name('api.')
                ->middleware(['auth:sanctum'])
                ->group(base_path('routes/api/api_auth.php'));
        },
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        App\Providers\AppServiceProvider::class,
    ])
    ->create();
