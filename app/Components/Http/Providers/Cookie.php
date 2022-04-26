<?php
/**
 * Central cookie control, delete, create and get.
 *
 * @package Simpler
 * @subpackage HTTP
 * @version 2.0
 */

namespace Simpler\Components\Http\Providers;

use Simpler\Components\Http\Providers\Interfaces\CookieInterface;

class Cookie extends ProviderManager implements CookieInterface
{
    /** @var string */
    protected string $driver = 'cookie';

    /**
     * Remove all cookies.
     *
     * @return bool
     */
    public function clear(): bool
    {
        if (isset($_COOKIE)) {
            $cookies = $_COOKIE;
            unset ($cookies[session_name()]);

            foreach ($_COOKIE as $cookie => $value) {
                $deleted = $this->delete($cookie);

                if (!$deleted) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
