<?php

namespace Simpler\Components\Exceptions;

use RuntimeException;
use Throwable;

class ThrowException extends RuntimeException
{
    public function __construct($e, Throwable $previous = null)
    {
        parent::__construct($e->getMessage(), (int) $e->getCode(), $previous);
    }
}
