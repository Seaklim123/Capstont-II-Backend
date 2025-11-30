<?php

use App\Http\Controllers\Backend\auth\AuthController;
use App\Http\Controllers\Backend\cart\CartController;
use App\Http\Controllers\Backend\category\CategoryController;
use App\Http\Controllers\backend\orders\OrderController;
use App\Http\Controllers\Backend\orders\PaymentController;
use App\Http\Controllers\Backend\product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
