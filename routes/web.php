<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Simple health check for API backend
Route::get('/', function () {
    return response()->json([
        'message' => 'TOS Order API Backend is running!',
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Debug route to test API routing
Route::post('/api/v1/auth/register-debug', function () {
    return response()->json([
        'message' => 'Debug endpoint reached!',
        'timestamp' => now(),
        'routes_loaded' => 'yes'
    ]);
});



Route::get('/debug-routes', function () {
    $routes = [];
    foreach (Route::getRoutes() as $route) {
        if (strpos($route->uri(), 'api/v1') !== false) {
            $routes[] = [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'name' => $route->getName(),
                'action' => $route->getActionName()
            ];
        }
    }
    return response()->json([
        'api_routes' => $routes,
        'total_count' => count($routes)
    ]);
});
