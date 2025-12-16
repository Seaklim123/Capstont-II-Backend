<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            // Public routes (register, login, health, categories, products, etc.)
            Route::prefix('api/v1')
                ->name('api.')
                ->group(base_path('routes/api/api.php'));

            // Protected authenticated routes
            Route::prefix('api/v1')
                ->name('api.')
                ->middleware(['auth:sanctum', 'check.user.status'])
                ->group(base_path('routes/api/api_auth.php'));
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
    })->create();
