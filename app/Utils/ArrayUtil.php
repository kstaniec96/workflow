<?php
/**
 * This class is helpers for array.
 *
 * @package Simpler
 * @subpackage Utils
 * @version 2.0
 */

namespace Simpler\Utils;

use Simpler\Utils\Interfaces\ArrayUtilInterface;

class ArrayUtil implements ArrayUtilInterface
{
    /**
     * @param array $array
     * @return bool
     */
    public static function isMulti(array $array): bool
    {
        rsort($array);

        return isset($array[0]) && is_array($array[0]);
    }

    /**
     * @param $key
     * @param array $array
     * @return bool
     */
    public static function searchKey($key, array $array): bool
    {
        foreach ($array as $_key => $item) {
            if (compare($_key, $key)) {
                return true;
            }

            if (is_array($item) && self::searchKey($item, $key)) {
                return true;
            }
        }

        return false;
    }
}
