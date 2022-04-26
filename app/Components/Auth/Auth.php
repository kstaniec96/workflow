<?php
/**
 * Authorization user.
 *
 * @package Simpler
 * @subpackage Auth
 * @version 2.0
 */

namespace Simpler\Components\Auth;

use Simpler\Components\Database\Model;
use Simpler\Components\Auth\Interfaces\AuthInterface;
use Exception;
use Project\Models\User;
use RuntimeException;

class Auth implements AuthInterface
{
    /**
     * Login user to web.
     *
     * @param object|User $user
     * @return bool
     */
    public static function login(object $user): bool
    {
        return session()->create(self::getSessionName(), $user->id);
    }

    /**
     * Refresh auth session.
     *
     * @return bool
     */
    public static function refresh(): bool
    {
        return session()->refresh(self::getSessionName(), self::id());
    }

    /**
     * Get logged user data.
     *
     * @param mixed $relations
     * @return null|Model
     */
    public static function user($relations = null): ?Model
    {
        if (self::checkSession()) {
            try {
                /** @var User $user */
                $user = User::query();

                return $user->with($relations)->where($user->getTableAlias().'.id', self::id());
            } catch (Exception $e) {
                throw new RuntimeException($e->getMessage());
            }
        }

        return null;
    }

    /**
     * Get logged user id.
     *
     * @return int|null
     */
    public static function id(): ?int
    {
        try {
            return session(self::getSessionName()) ?? AuthToken::id();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Check user is logged in.
     *
     * @return bool
     */
    public static function check(): bool
    {
        if (self::checkSession()) {
            return true;
        }

        return false;
    }

    /**
     * Check user is not logged in.
     *
     * @return bool
     */
    public static function guest(): bool
    {
        return !self::check();
    }

    /**
     * Logout user in web.
     *
     * @return bool
     */
    public static function logout(): bool
    {
        if (self::checkSession()) {
            session()->clear(true);

            return true;
        }

        return false;
    }

    /**
     * Check current user session.
     *
     * @return bool
     */
    private static function checkSession(): bool
    {
        return session()->has(self::getSessionName()) || !is_null(AuthToken::id());
    }

    /**
     * Get auth session name.
     *
     * @return string
     */
    private static function getSessionName(): string
    {
        return 'auth.login_user_'.projectKey();
    }
}
