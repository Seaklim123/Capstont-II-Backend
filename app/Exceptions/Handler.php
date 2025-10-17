<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof TableNumberNotFoundException) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return parent::render($request, $e);
    }
}
