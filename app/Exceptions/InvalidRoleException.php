<?php

namespace App\Exceptions;

use Exception;

class InvalidRoleException extends Exception
{
    protected $message = 'Invalid role specified';
}
