<?php

use App\Http\Controllers\Backend\auth\AuthController;
use App\Http\Controllers\Backend\product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::prefix('products')->group(function () {
        Route::get('/top-products', [ProductController::class, 'topProducts'])->name('topProducts');
    });
});

// routes/web.php
Route::get('/health', function () {
    return "Good!!";
});

