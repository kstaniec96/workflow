<?php
/**
 * A PHP class to access a PHP
 * array via dot notation.
 *
 * @package Simpler
 * @version 2.0
 *
 * @see https://selvinortiz.com/blog/traversing-arrays-using-dot-notation
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\DotNotationInterface;

class DotNotation implements DotNotationInterface
{
    /**
     * Converts dot notation to an
     * array and then returns the array value.
     *
     * @param string $key
     * @param array $data
     * @param string|null $default
     * @param bool $array
     * @return array|array[]|mixed|string|null
     */
    public static function set(string $key, array $data, ?string $default = null, bool $array = false)
    {
        if (empty($key) || empty($data)) {
            return $default;
        }

        // @assert $key contains a dot notated string
        if (self::check($key)) {
            $keys = self::toArray($key);

            foreach ($keys as $innerKey) {
                // @assert $data[$innerKey] is available to continue
                // @otherwise return $default value
                if (!array_key_exists($innerKey, $data)) {
                    return $default;
                }

                $data = $data[$innerKey];

                // The last key gets.
                if ($array) {
                    $key = $innerKey;
                }
            }

            return !$array ? $data : array($key => $data);
        }

        // @fallback returning value of $key in $data or $default value
        if (array_key_exists($key, $data)) {
            $data = $data[$key];

            return !$array ? $data : array($key => $data);
        }

        return $default;
    }

    /**
     * Get dot notation as array.
     *
     * @param mixed $data
     * @return mixed
     */
    public static function toArray($data)
    {
        if (is_string($data)) {
            return explode('.', $data);
        }

        return $data;
    }

    /**
     * Get do notation as string.
     *
     * @param mixed $data
     * @return mixed
     */
    public static function toString($data)
    {
        if (is_array($data)) {
            return implode('.', $data);
        }

        return $data;
    }

    /**
     * Check string is dot notation.
     *
     * @param string|null $string
     * @return bool
     */
    public static function check(?string $string): bool
    {
        if (empty($string)) {
            return false;
        }

        return strpos($string, '.') !== false;
    }
}
