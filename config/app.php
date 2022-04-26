<?php
/**
 * App global settings.
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 */

use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Http\Middlewares\AuthMiddleware;
use Simpler\Components\Http\Middlewares\GuestMiddleware;
use Monolog\Logger;

return [
    /*
     * Settings for default middlewares.
     */
    'middlewares' => [
        AuthMiddleware::class => [
            // In case of "false", an exception will be shown.
            'redirect' => 'home.index',
        ],

        GuestMiddleware::class => [
            // In case of "false", an exception will be shown.
            'redirect' => 'user.home.index',
        ],
    ],

    /*
     * Settings for locale.
     */
    'locale' => [
        /*
         * The language in which the page will be tested.
         * If it is false, the tests will be turned off.
         */
        'test' => false,

        /*
         * The default language in which the page
         * should be displayed in case the user's
         * language is not declared.
         */
        'default' => 'en',

        /**
         * Default time zone for the project.
         */
        'timezone' => 'Europe/Warsaw',
    ],

    /*
     * Settings for logger.
     */
    'logger' => [
        /**
         * The logging channel, a simple descriptive name that is attached to all log records.
         */
        'channel' => env('PROJECT_NAME', 'name').'_logger',

        /*
         * f a missing path can't be created, an UnexpectedValueException will be thrown on first write.
         */
        'stream' => storagePath('logs/logs_'.date('d_m_Y').'.log'),

        /**
         * Default logger level.
         */
        'level' => Logger::DEBUG,
    ],

    /**
     * Settings for breaks the script and displays an error.
     */
    'abort' => [
        /**
         * In case of an unsupported http status.
         */
        'defaultHttpStatus' => HttpStatus::BAD_REQUEST,

        /**
         * HTTP status supported, otherwise returns default error code.
         */
        'supportedHttpStatuses' => [
            HttpStatus::INTERNAL_SERVER_ERROR,
            HttpStatus::NOT_FOUND,
            HttpStatus::BAD_REQUEST,
            HttpStatus::PAGE_EXPIRED,
            HttpStatus::FORBIDDEN,
            HttpStatus::BAD_REQUEST,
        ],
    ],
];
