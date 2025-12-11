<?php

use App\Http\Controllers\Backend\auth\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    // Authentication routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Test routes
    Route::get('/hello', [AuthController::class, 'sayHello']);
    Route::post('/test', [AuthController::class, 'hello']); // Test POST method
});
