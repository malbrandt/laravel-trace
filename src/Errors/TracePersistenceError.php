<?php

namespace Malbrandt\Laravel\Trace\Errors;

use Exception;
use Throwable;

class TracePersistenceError extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
