<?php

declare(strict_types=1);

namespace Project\Exceptions;

use Exception;
use Throwable;

class ExampleException extends Exception
{
    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? 'Example exception message', $code, $previous);
    }
}
