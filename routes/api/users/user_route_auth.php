<?php

use App\Http\Controllers\Backend\cart\CartController;
use App\Http\Controllers\Backend\category\CategoryController;
use App\Http\Controllers\backend\orders\OrderController;
use App\Http\Controllers\Backend\orders\OrderListController;
use App\Http\Controllers\Backend\orders\PaymentController;
use App\Http\Controllers\Backend\product\ProductController;
use App\Http\Controllers\Backend\table\TableNumberController;
use App\Http\Controllers\Backend\user\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {
    Route::get('/hello', [TableNumberController::class, 'hello']);
    // Tables
    Route::prefix('tables')->group(function () {
        Route::get('/', [TableNumberController::class, 'index']);
        Route::get('/{id}', [TableNumberController::class, 'show']);
        Route::post('/', [TableNumberController::class, 'store']);
        Route::put('/{id}', [TableNumberController::class, 'update']);
        Route::delete('/{id}', [TableNumberController::class, 'destroy']);
    });
    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'cashier']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Category
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
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
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::get('/status', [OrderController::class, 'getByStatus']);
        Route::get('/findByNumber/{id}', [OrderController::class, 'findByNumber']);
        Route::put('/markAsDone/{id}', [OrderController::class, 'markAsDone']);
        Route::get('/checkOrder', [OrderController::class, 'checkOrder']);
        Route::put('/cancelOrder/{id}', [OrderController::class, 'cancelOrder']);
    });
    Route::prefix('payment')->group(function () {
        Route::post('/create', [PaymentController::class, 'createPayment']);
        Route::get('/success', [PaymentController::class, 'paymentSuccess']);
        Route::get('/cancel', [PaymentController::class, 'paymentCancel']);
    });


});
