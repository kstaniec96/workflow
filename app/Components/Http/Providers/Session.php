<?php
/**
 * Central session control, delete, create and get.
 *
 * @package Simpler
 * @subpackage HTTP
 * @version 2.0
 */

namespace Simpler\Components\Http\Providers;

use Simpler\Components\Http\Providers\Interfaces\SessionInterface;

class Session extends ProviderManager implements SessionInterface
{
    /** @var string */
    protected string $driver = 'session';

    /**
     * Set basic session settings.
     *
     * @param bool $regenerate
     * @return void
     */
    public function start(bool $regenerate = true): void
    {
        // Set session name
        session_name($this->config['name']);

        // Sets the session cookie params.
        session_set_cookie_params(
            $this->config['lifetime'] * 60,
            $this->config['path'],
            $this->config['domain'],
            $this->config['secure'],
            $this->config['httponly']
        );

        // Session start
        if (compare(session_status(), PHP_SESSION_NONE)) {
            session_start();
        }

        // Session regenerate id
        if ($regenerate) {
            session_regenerate_id();
        }

        if (isset($_SESSION)) {
            $this->controller();
        }
    }

    /**
     * Removes all active sessions
     * and session cookies.
     *
     * @param bool $logout
     * @return bool
     */
    public function clear(bool $logout = false): bool
    {
        // If a cookie is used to pass the session id.
        if (ini_get('session.use_cookies')) {
            cookie()->delete(session_name());
        }

        // Destroy the session.
        if (isset($_SESSION)) {
            session_start();
            session_unset();
            session_destroy();
            session_write_close();

            unset($_SESSION);
        } else {
            return false;
        }

        return !isset($_SESSION);
    }

    /**
     * Session items controller.
     *
     * @return void
     */
    private function controller(): void
    {
        foreach (($_SESSION[self::EXPIRES] ?? []) as $group => $data) {
            if (empty($data)) {
                unset($_SESSION[self::EXPIRES][$group], $_SESSION[$group]);
            }

            if (is_array($data)) {
                foreach ($data as $id => $time) {
                    if (is_null($_SESSION[$group][$id] ?? null) || $time < time()) {
                        unset($_SESSION[self::EXPIRES][$group][$id], $_SESSION[$group][$id]);
                    }
                }
            }
        }
    }
}
