<?php

namespace Simpler\Components\Exceptions;

use RuntimeException;
use Simpler\Components\Enums\HttpStatus;
use Throwable;

class ServerErrorException extends RuntimeException
{
    public function __construct($message = null, $code = HttpStatus::INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
