<?php
/**
 * This class is used to hash, verify and rehash the password.
 *
 * @package Simpler
 * @subpackage Security
 * @version 2.0
 */

namespace Simpler\Components\Security;

use Simpler\Components\Config;
use Simpler\Components\Security\Interfaces\HashInterface;

class Hash implements HashInterface
{
    /**
     * Creates a password hash.
     *
     * @param string $password
     * @param string|null $algorithm
     * @param array|null $options
     * @return null|string
     */
    public static function make(string $password, ?string $algorithm = null, ?array $options = null): ?string
    {
        if (empty($password)) {
            return null;
        }

        $hash = Config::get('hash');

        return password_hash($password, $algorithm ?? $hash['algorithm'], $options ?? $hash['options']);
    }

    /**
     * Checks if the given hash matches the given options.
     *
     * @param string|null $password
     * @param string $hash
     * @return bool
     */
    public static function verify(?string $password, string $hash): bool
    {
        if (empty($password)) {
            return false;
        }

        return password_verify($password, $hash);
    }

    /**
     * Checks if the given hash matches the given options.
     *
     * @param string|null $password
     * @param string|null $algorithm
     * @param array|null $options
     * @return bool
     */
    public static function rehash(string $password, ?string $algorithm = null, ?array $options = null): bool
    {
        if (empty($password)) {
            return false;
        }

        $hash = Config::get('hash');

        return password_needs_rehash(
            $password,
            $algorithm ?? $hash['algorithm'],
            $options ?? $hash['options']
        );
    }
}
