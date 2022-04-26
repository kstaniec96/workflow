<?php
/**
 * Settings for hash.
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 */

return [
    /*
     * Password encoding algorithm
     */
    'algorithm' => PASSWORD_BCRYPT,

    /**
     * Supported options for PASSWORD_BCRYPT
     */
    'options' => [
        /*
         * Which denotes the algorithmic cost that should be used.
         */
        'cost' => 11,

        /*
         * Maximum memory (in kibibytes) that may be used to compute the Argon2 hash.
         */
        'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,

        /*
         * Maximum amount of time it may take to compute the Argon2 hash.
         */
        'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,

        /*
         * Number of threads to use for computing the Argon2 hash.
         */
        'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
    ],
];
