<?php

namespace Simpler\Utils\Interfaces;

interface ArrayUtilInterface
{
    /**
     * @param array $array
     * @return bool
     */
    public static function isMulti(array $array): bool;

    /**
     * @param $key
     * @param array $array
     * @return bool
     */
    public static function searchKey($key, array $array): bool;
}
