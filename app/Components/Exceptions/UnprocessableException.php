<?php

namespace Simpler\Components\Exceptions;

use RuntimeException;
use Simpler\Components\Enums\HttpStatus;
use Throwable;

class UnprocessableException extends RuntimeException
{
    public function __construct($message = null, $code = HttpStatus::UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
