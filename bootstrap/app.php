<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            // Public API routes (no auth required)
            Route::prefix('api/v1')
                ->name('api.')
                ->group(base_path('routes/api/api.php'));

            // Protected API routes with authentication
            Route::prefix('api/v1')
                ->name('api.')
                ->middleware(['auth:sanctum', 'check.user.status'])
                ->group(base_path('routes/api/api_auth.php'));
                
            // Web routes for health check
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckUserRole::class,
            'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        App\Providers\AppServiceProvider::class,
    ])
    ->create();
