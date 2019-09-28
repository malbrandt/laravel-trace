<?php


namespace Malbrandt\Laravel\Trace\Errors;


use Exception;
use Throwable;

class InvalidContractImplementation extends Exception
{
    public function __construct($expectedClass, $receivedClass, $code = 0, Throwable $previous = null)
    {
        $message = "Invalid contract implementation. Tip: received class [{$receivedClass}] but expected [{$expectedClass}].";

        parent::__construct($message, $code, $previous);
    }
}
