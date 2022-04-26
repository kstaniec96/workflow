<?php
/**
 * This class is handler fot exceptions.
 *
 * @package Simpler
 * @subpackage Exceptions
 * @version 2.0
 *
 * @see https://github.com/filp/whoops
 * @see https://github.com/Seldaek/monolog
 */

namespace Simpler\Components\Exceptions;

use Simpler\Components\Config;
use Simpler\Components\Enums\HttpStatus;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class HandlerException implements HandlerExceptionInterface
{
    /**
     * Register exceptions handler - whoops.
     *
     * @return void
     */
    public static function whoops(): void
    {
        $isApi = false;

        $whoops = new Run;
        $handler = new PrettyPageHandler();
        $handler->setEditor(PrettyPageHandler::EDITOR_ATOM);

        if (request()->isApi()) {
            $isApi = true;
            $handler = new JsonResponseHandler();
        }

        $whoops->pushHandler($handler)->pushHandler(
            new CallbackHandler(static function ($error) use ($isApi) {
                self::logger($error);

                if (!env('PROJECT_DEBUG')) {
                    if ($isApi) {
                        response()->error($error);
                    }

                    self::abort(HttpStatus::INTERNAL_SERVER_ERROR);
                }
            })
        );

        $whoops->register();
    }

    /**
     * Log errors
     *
     * @param mixed $message
     * @param mixed $level
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    public static function logger($message, $level = Logger::ERROR, array $context = [], ?string $channel = null): void
    {
        $config = Config::get('app.logger');
        $logger = new Logger($channel ?? $config['channel']);

        $logger->pushHandler(new StreamHandler($config['stream'], $config['level']));
        $logger->pushHandler(new FirePHPHandler());

        $logger->log($level, $message, $context);
    }

    /**
     * Breaks the script and displays an error.
     *
     * @param int $code
     * @return void
     */
    public static function abort(int $code = HttpStatus::NOT_FOUND): void
    {
        $config = Config::get('app.abort');

        if (!in_array($code, $config['supportedHttpStatuses'], true)) {
            $code = $config['defaultHttpStatus'];
        }

        response()->status($code);

        import(ERRORS_PATH.DS.$code.'.html', true);
        exit;
    }
}
