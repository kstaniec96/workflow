<?php

namespace Simpler\Components\Security\Interfaces;

interface FilterInterface
{
    /**
     * @param string $input
     * @param bool $strip
     * @return string
     */
    public static function url(string $input, bool $strip = true): string;

    /**
     * @param $values
     * @param bool $trim
     * @return mixed
     */
    public static function clear($values, bool $trim = true);

    /**
     * @param string $path
     * @return string
     */
    public static function path(string $path): string;
}
