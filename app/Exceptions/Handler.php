<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // Example for Handler.php update (Conceptual code)
    public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response
    {
        // List all your custom exceptions that should return a specific JSON format
        if ($e instanceof TableNumberNotFoundException ||
            $e instanceof InvalidCredentialsException ||
            $e instanceof UserNotFoundException)
        {
            // Use getCode() to retrieve the defined 400 or 404 status
            $status = $e->getCode() ?: 500; // Fallback to 500 if code is 0/null

            return response()->json([
                'message' => $e->getMessage()
            ], $status);
        }

        return parent::render($request, $e);
    }
}
