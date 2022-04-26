<?php

namespace Simpler\Components\Interfaces;

interface DotNotationInterface
{
    /**
     * @param string $key
     * @param array $data
     * @param string|null $default
     * @param bool $array
     * @return mixed
     */
    public static function set(string $key, array $data, ?string $default = null, bool $array = false);

    /**
     * @param mixed $data
     * @return mixed
     */
    public static function toArray($data);

    /**
     * @param mixed $data
     * @return mixed
     */
    public static function toString($data);

    /**
     * @param string|null $string
     * @return bool
     */
    public static function check(?string $string): bool;
}
