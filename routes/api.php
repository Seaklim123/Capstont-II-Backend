<?php

use App\Http\Controllers\backend\cart\CartController;
use App\Http\Controllers\backend\category\CategoryController;
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