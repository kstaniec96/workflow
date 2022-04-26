<?php
/**
 * Settings for cookies.
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 */

$secure = !empty($_SERVER['HTTPS']) && !compare($_SERVER['HTTPS'], 'off');
$lifetime = env('COOKIE_LIFETIME', 120);

return [
    /**
     * The path on the server in which the
     * cookie will be available on.
     */
    'path' => '/',

    /**
     * The (sub)domain that the cookie is
     * available to (recommended).
     */
    'domain' => $_SERVER['SERVER_NAME'],

    /**
     * Indicates that the cookie should only be transmitted
     * over a secure HTTPS connection from the client.
     */
    'secure' => $secure,

    /**
     * When TRUE the cookie will be made accessible
     * only through the HTTP protocol.
     */
    'httponly' => true,

    /**
     * Additional group of the cookie being created.
     */
    'group' => env('COOKIE_GROUP_NAME', 'cookies'),

    /**
     * The default expire of the cookie.
     */
    'expire' => env('COOKIE_EXPIRE', $lifetime.' minutes'),

    /**
     * Settings for session flash.
     */
    'flash' => [
        /**
         * Additional group of the flash cookie being created.
         */
        'group' => env('COOKIE_FLASH_GROUP_NAME', 'flash'),

        /**
         * The default expire of the flash cookie.
         */
        'expire' => env('COOKIE_FLASH_EXPIRE', '3 seconds'),
    ],
];
