<?php

use App\Http\Controllers\Backend\auth\AuthController;
use App\Http\Controllers\Backend\cart\CartController;
use App\Http\Controllers\Backend\category\CategoryController;
use App\Http\Controllers\Backend\orders\OrderListController;
use App\Http\Controllers\Backend\product\ProductController;
use App\Http\Controllers\Backend\table\TableNumberController;
use App\Http\Controllers\Backend\user\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
});

// Admin-only routes
Route::prefix('admin')->middleware('role:admin')->group(function () {

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/statistics', [UserController::class, 'statistics'])->name('statistics');
        Route::get('/active', [UserController::class, 'activeUsers'])->name('active');
        Route::get('/admins', [UserController::class, 'admins'])->name('admins');
        Route::get('/cashiers', [UserController::class, 'cashiers'])->name('cashiers');
        Route::get('/search', [UserController::class, 'search'])->name('search');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/cashier', [UserController::class, 'cashier'])->name('create.cashier');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle.status');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Tables
    Route::prefix('tables')->group(function () {
        Route::get('/', [TableNumberController::class, 'index']);
        Route::get('/{id}', [TableNumberController::class, 'show']);
        Route::post('/', [TableNumberController::class, 'store']);
        Route::put('/{id}', [TableNumberController::class, 'update']);
        Route::delete('/{id}', [TableNumberController::class, 'destroy']);
    });

    // Categories
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
});

// Cashier routes
Route::prefix('cashier')->middleware('role:cashier')->group(function () {

    // Carts (full access)
    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/{id}', [CartController::class, 'show']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });

    // Orders (full access)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderListController::class, 'index']);
        Route::get('/{id}', [OrderListController::class, 'show']);
        Route::post('/', [OrderListController::class, 'store']);
        Route::put('/{id}', [OrderListController::class, 'update']);
        Route::delete('/{id}', [OrderListController::class, 'destroy']);
        Route::get('/status', [OrderListController::class, 'getByStatus']);
        Route::get('/findByNumber/{id}', [OrderListController::class, 'findByNumber']);
        Route::put('/markAsDone/{id}', [OrderListController::class, 'markAsDone']);
        Route::get('/checkOrder', [OrderListController::class, 'checkOrder']);
        Route::get('/cancelOrder', [OrderListController::class, 'cancelOrder']);
    });

    // View-only access to products, categories, tables
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/tables', [TableNumberController::class, 'index']);
    Route::get('/tables/{id}', [TableNumberController::class, 'show']);
});
