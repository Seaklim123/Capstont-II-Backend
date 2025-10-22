<?php

namespace App\Exceptions;

use Exception;

class OrderListNotFoundException extends Exception
{
    protected $message = 'Order list not found';
    protected $code = 404;
}
