<?php

use App\Http\Controllers\backend\auth\AuthController;
use App\Http\Controllers\backend\cart\CartController;
use App\Http\Controllers\backend\category\CategoryController;
use App\Http\Controllers\backend\orders\OrderController;
use App\Http\Controllers\backend\product\ProductController;
use App\Http\Controllers\backend\table\TableNumberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check endpoint for frontend
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Backend is running',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Authentication routes (public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::get('/products/best-sellers/list', [ProductController::class, 'bestSellers'])->name('products.bestSellers');
Route::get('/products/discounts/list', [ProductController::class, 'discounts'])->name('products.discounts');
Route::apiResource('tables', TableNumberController::class);
Route::apiResource('carts', CartController::class);

Route::prefix('orders')->group(function () {
    // 🧾 Create a new order from table carts
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');

    // 🧩 (Optional) Get all orders
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');

    // 📋 (Optional) Show a single order by ID
    Route::get('/status', [OrderController::class, 'getByStatus'])->name('orders.getByStatus');
    Route::get('/show/{id}', [OrderController::class, 'checkOrder'])->name('orders.cheack');
    Route::get('/number/{id}', [OrderController::class, 'findByNumber'])->name('orders.number');
    Route::put('/listorder/{id}', [OrderController::class, 'cancelOrder'])->name('orders.cancel');

    // ✅ (Optional) Mark order as done
    Route::put('/{id}', [OrderController::class, 'markAsDone'])->name('orders.done');
});