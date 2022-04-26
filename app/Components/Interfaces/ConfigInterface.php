<?php

namespace Simpler\Components\Interfaces;

interface ConfigInterface
{
    /**
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    public static function get(string $key, string $default = null);
}
