<?php

namespace Simpler\Components\Auth\Interfaces;

use Simpler\Components\Auth\AuthToken;

interface AuthTokenInterface
{
    /**
     * @param null|object $user
     * @return AuthToken
     */
    public static function generate(?object $user = null): AuthToken;

    /**
     * @return AuthToken
     */
    public static function refresh(): AuthToken;

    /**
     * @return bool
     */
    public static function check(): bool;

    /**
     * @return string|null
     */
    public static function id(): ?string;

    /**
     * @return string
     */
    public function token(): string;

    /**
     * @return int
     */
    public function expire(): int;
}
