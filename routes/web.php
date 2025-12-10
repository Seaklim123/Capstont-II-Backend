<?php

use Illuminate\Support\Facades\Route;

// Simple health check for API backend
Route::get('/', function () {
    return response()->json([
        'message' => 'TOS Order API Backend is running!',
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});
