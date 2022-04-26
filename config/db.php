<?php
/**
 * Database configuration
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 */

return [
    /**
     * All database connections.
     */
    'connections' => [
        /**
         * Default database connection.
         */
        'default' => [
            /**
             * SQL driver
             */
            'driver' => env('DB_DRIVER', 'mysql'),

            /**
             * Accepts the host name on which
             * the database resides.
             */
            'host' => env('DB_HOST', 'localhost'),

            /**
             * The name of the database on which the query
             * structure is based.
             */
            'dbname' => env('DB_NAME', 'dbname'),

            /**
             * Database login information.
             */
            'user' => env('DB_USER', 'dbuser'),
            'pass' => env('DB_PASS', 'dbpass'),

            /**
             * Database port access.
             */
            'port' => env('DB_PORT', 3306),

            /**
             * Tables prefix
             */
            'prefix' => env('DB_PREFIX', ''),

            /**
             * Table charset
             */
            'charset' => env('DB_CHARSET', 'utf8'),

            /**
             * PDO extension options.
             */
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ],

        /**
         * Others data to connect
         * to the database.
         */
        'slave' => [
            /**
             * SQL driver
             */
            'driver' => env('DB_SLAVE_DRIVER', 'mysql'),

            /**
             * Accepts the host name on which
             * the database resides.
             */
            'host' => env('DB_SLAVE_HOST', 'localhost'),

            /**
             * The name of the database on which the query
             * structure is based.
             */
            'dbname' => env('DB_SLAVE_NAME', 'dbname'),

            /**
             * Database login information.
             */
            'user' => env('DB_SLAVE_USER', 'dbuser'),
            'pass' => env('DB_SLAVE_PASS', 'dbpass'),

            /**
             * Database port access.
             */
            'port' => env('DB_SLAVE_PORT', 3306),

            /**
             * Tables prefix
             */
            'prefix' => env('DB_SLAVE_PREFIX', ''),

            /**
             * Table charset
             */
            'charset' => env('DB_SLAVE_CHARSET', 'utf8'),

            /**
             * PDO extension options.
             */
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ],
    ],

    /**
     * Options for migrations.
     */
    'migrations' => [
        /*
         * Maximum number of characters in the migration name.
         */
        'maxCharsName' => 300,

        /*
         * The path under which the migrations are located.
         */
        'path' => ROOT_PATH.DS.'db'.DS.'migrations',
    ],

    /**
     * Options for fixtures.
     */
    'fixtures' => [
        /*
        * Maximum number of characters in the fixture name.
        */
        'maxCharsName' => 200,

        /*
         * The path under which the fixtures are located.
         */
        'path' => ROOT_PATH.DS.'db'.DS.'fixtures',
    ],
];
