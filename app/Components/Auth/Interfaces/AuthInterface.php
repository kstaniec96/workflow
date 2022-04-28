<?php

namespace Simpler\Components\Auth\Interfaces;

use Simpler\Components\Database\Model;

interface AuthInterface
{
    /**
     * @param object $user
     * @return bool
     */
    public static function login(object $user): bool;

    /**
     * @return bool
     */
    public static function refresh(): bool;

    /**
     * @param mixed $relations
     * @return Model
     */
    public static function user($relations = null): Model;

    /**
     * @return int|null
     */
    public static function id(): ?int;

    /**
     * @return bool
     */
    public static function check(): bool;

    /**
     * @return bool
     */
    public static function guest(): bool;

    /**
     * @return bool
     */
    public static function logout(): bool;
}
