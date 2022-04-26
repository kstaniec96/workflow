<?php

namespace Simpler\Components\Exceptions;

use Simpler\Components\Enums\HttpStatus;
use Monolog\Logger;

interface HandlerExceptionInterface
{
    /**
     * @return void
     */
    public static function whoops(): void;

    /**
     * @param mixed $message
     * @param mixed $level
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    public static function logger($message, $level = Logger::ERROR, array $context = [], ?string $channel = null): void;

    /**
     * @param int $code
     * @return void
     */
    public static function abort(int $code = HttpStatus::NOT_FOUND): void;
}
