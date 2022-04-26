<?php
/**
 * Settings for creating session.
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 */

$secure = !empty($_SERVER['HTTPS']) && !compare($_SERVER['HTTPS'], 'off');
$lifetime = env('SESSION_LIFETIME', 120);

return [
    /**
     * Lifetime of the session cookie.
     */
    'lifetime' => $lifetime,

    /**
     * The path on the server in which the
     * cookie will be available on.
     */
    'path' => '/',

    /**
     * The (sub)domain that the cookie
     * is available to (recommended).
     *
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
     * Sets the name 'session_name'.
     */
    'name' => env('SESSION_NAME', 'SSID'),

    /**
     * Additional group of the session being created.
     */
    'group' => env('SESSION_GROUP_NAME', 'sessions'),

    /**
     * The default expire of the session.
     */
    'expire' => env('SESSION_EXPIRE', $lifetime.' minutes'),

    /**
     * Settings for session flash.
     */
    'flash' => [
        /**
         * Additional group of the flash session being created.
         */
        'group' => env('SESSION_FLASH_GROUP_NAME', 'flash'),

        /**
         * The default expire of the flash session.
         */
        'expire' => env('SESSION_FLASH_EXPIRE', '1 seconds'),
    ],
];
