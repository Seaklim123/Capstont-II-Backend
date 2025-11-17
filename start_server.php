<?php
// Alternative artisan command runner
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

echo "Starting Laravel server...\n";

try {
    // Create application instance
    $app = Application::configure(basePath: __DIR__)
        ->withRouting(
            web: __DIR__.'/routes/web.php',
            api: __DIR__.'/routes/api.php',
            commands: __DIR__.'/routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
            //
        })
        ->withExceptions(function (Exceptions $exceptions) {
            //
        })->create();

    // Start development server
    echo "Laravel development server started on http://localhost:8000\n";
    echo "Press Ctrl+C to stop the server\n\n";
    
    // Use PHP built-in server
    $command = "php -S localhost:8000 -t public";
    passthru($command);
    
} catch (Exception $e) {
    echo "Error starting server: " . $e->getMessage() . "\n";
    echo "Trying alternative approach...\n";
    
    // Fallback to simple PHP server
    $command = "php -S localhost:8000 -t public public/index.php";
    passthru($command);
}
?>