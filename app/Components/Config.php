<?php
/**
 * This class get config from file app.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\ConfigInterface;

class Config implements ConfigInterface
{
    /** @var string */
    private const CONFIG_FILE = ROOT_PATH.DS.'config'.DS;

    /**
     * Get config value from key.
     *
     * @param string $key
     * @param null|string $default
     * @return mixed
     */
    public static function get(string $key, string $default = null)
    {
        $explodeKey = explode('.', $key, 2);
        $config = import(self::CONFIG_FILE.$explodeKey[0].'.php', true);

        $name = $explodeKey[1] ?? null;

        if (!empty($name)) {
            return DotNotation::set($name, $config) ?? $default;
        }

        return $config ?? $default;
    }
}
