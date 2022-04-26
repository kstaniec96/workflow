<?php
/**
 * Middleware for API user authorization.
 *
 * @package Simpler
 * @subpackage Middlewares
 * @version 2.0
 */

namespace Simpler\Components\Http\Middlewares;

use Simpler\Components\Auth\AuthToken;

class AuthApiMiddleware
{
    /**
     * @return void
     */
    public function handle(): void
    {
        AuthToken::check();
    }
}
