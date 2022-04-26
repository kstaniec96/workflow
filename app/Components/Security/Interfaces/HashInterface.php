<?php

namespace Simpler\Components\Security\Interfaces;

interface HashInterface
{
    /**
     * @param string $password
     * @param string|null $algorithm
     * @param array|null $options
     * @return null|string
     */
    public static function make(string $password, ?string $algorithm = null, ?array $options = null): ?string;

    /**
     * @param string|null $password
     * @param string $hash
     * @return bool
     */
    public static function verify(?string $password, string $hash): bool;

    /**
     * @param string $password
     * @param string|null $algorithm
     * @param array|null $options
     * @return bool
     */
    public static function rehash(string $password, ?string $algorithm = null, ?array $options = null): bool;
}
