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

// Debug database config
Route::get('/v1/debug-db', function () {
    return response()->json([
        'DATABASE_URL_exists' => env('DATABASE_URL') ? 'YES' : 'NO',
        'DATABASE_URL_length' => env('DATABASE_URL') ? strlen(env('DATABASE_URL')) : 0,
        'DATABASE_URL_preview' => env('DATABASE_URL') ? substr(env('DATABASE_URL'), 0, 50) . '...' : 'NOT_SET',
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'current_db_config' => config('database.connections.pgsql'),
        'default_connection' => config('database.default')
    ]);
});


