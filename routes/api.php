<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Basic test endpoint for debugging
Route::post('/v1/test-basic', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Basic endpoint reached!',
        'received_data' => $request->all(),
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'environment' => app()->environment(),
        'timestamp' => now()
    ]);
});


