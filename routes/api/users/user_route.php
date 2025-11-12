<?php


use App\Http\Controllers\Backend\auth\AuthController;
use App\Http\Controllers\Backend\cart\CartController;
use App\Http\Controllers\backend\orders\OrderController;
use App\Http\Controllers\Backend\orders\PaymentController;
use App\Http\Controllers\Backend\product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });
    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/{id}', [CartController::class, 'show']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/status', [OrderController::class, 'getByStatus']);
        Route::get('/findByNumber/{id}', [OrderController::class, 'findByNumber']);
        Route::get('/checkOrder', [OrderController::class, 'checkOrder']);
    });
    Route::prefix('payment')->group(function () {
        Route::post('/create', [PaymentController::class, 'createPayment']);
        Route::get('/success', [PaymentController::class, 'paymentSuccess']);
        Route::get('/cancel', [PaymentController::class, 'paymentCancel']);
    });

});


