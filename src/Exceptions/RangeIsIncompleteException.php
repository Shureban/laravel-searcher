<?php

namespace Shureban\LaravelSearcher\Exceptions;

use Exception;
use Throwable;

class RangeIsIncompleteException extends Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        $message = 'Range does not contains enough elements';

        parent::__construct($message, $code, $previous);
    }
}
