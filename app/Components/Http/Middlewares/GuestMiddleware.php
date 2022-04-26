<?php
/**
 * Middleware for guest user.
 *
 * @package Simpler
 * @subpackage Middlewares
 * @version 2.0
 */

namespace Simpler\Components\Http\Middlewares;

use Simpler\Components\Auth\Auth;
use Simpler\Components\Config;
use RuntimeException;

class GuestMiddleware
{
    public function handle(): void
    {
        if (Auth::check()) {
            $config = Config::get('app.middlewares.'.__CLASS__.'.redirect');

            if ($config) {
                redirect()->to(route($config));
            }

            throw new RuntimeException(__('framework.errors.accessDenied'));
        }
    }
}
