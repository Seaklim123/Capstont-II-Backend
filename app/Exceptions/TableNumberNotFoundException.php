<?php

namespace App\Exceptions;

use Exception;

class TableNumberNotFoundException extends Exception
{
   protected $message = "Table number not found!";
   protected $code = 404;
}
