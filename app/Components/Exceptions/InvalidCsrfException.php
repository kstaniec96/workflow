<?php

namespace Simpler\Components\Exceptions;

use RuntimeException;
use Throwable;

class InvalidCsrfException extends RuntimeException
{
    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? 'Invalid CSRF token', $code, $previous);
    }
}
