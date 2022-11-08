<?php

namespace Shureban\LaravelSearcher\Exceptions;

use Exception;
use Throwable;

class WrongDateTimeFormatException extends Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        $message = 'Date/DateTime is not a valid';

        parent::__construct($message, $code, $previous);
    }
}
