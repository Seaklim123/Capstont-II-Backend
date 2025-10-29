<?php

use App\Http\Controllers\backend\cart\CartController;
use App\Http\Controllers\backend\category\CategoryController;
use App\Http\Controllers\backend\orders\OrderController;
use App\Http\Controllers\backend\product\ProductController;
use App\Http\Controllers\backend\table\TableNumberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
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

    // ✅ (Optional) Mark order as done
    Route::put('/{id}', [OrderController::class, 'markAsDone'])->name('orders.done');
});