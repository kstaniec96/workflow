<?php

namespace Simpler\Components\Exceptions;

use Simpler\Components\Enums\HttpStatus;
use RuntimeException;
use Throwable;

class AuthException extends RuntimeException
{
    public function __construct($message = null, $code = HttpStatus::UNAUTHORIZED, Throwable $previous = null)
    {
        parent::__construct($message ?? 'Unauthorized', $code, $previous);
    }
}
