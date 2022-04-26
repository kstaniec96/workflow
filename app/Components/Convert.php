<?php
/**
 * This class is used to convert:
 * - to URL,
 * - to server path,
 * - to timestamp,
 * - to date
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\ConvertInterface;
use Simpler\Components\Security\Filter;

class Convert implements ConvertInterface
{
    /**
     * @param string $path
     * @return string
     */
    public static function toURL(string $path): string
    {
        if (!filter_var($path, FILTER_VALIDATE_URL)) {
            $root = !compare(request()->ip(), '127.0.0.1') && !empty(ROOT_PATH) ? ROOT_PATH : '';
            $path = str_replace(filterPath(ROOT_PATH), url()->domain().$root, filterPath($path));
        }

        return Filter::url($path);
    }

    /**
     * @param string $url
     * @return string
     */
    public static function toServerPath(string $url): string
    {
        $path = str_replace(url()->domain().'/', filterPath(ROOT_PATH), $url);

        return filterPath($path);
    }

    /**
     * @param string $expire
     * @param string|null $custom_date
     * @param string $format
     * @return int
     */
    public static function toTimestamp(
        string $expire,
        ?string $custom_date = null,
        string $format = 'Y:m:d H:i:s'
    ): int {
        return !empty($custom_date) ? strtotime($custom_date.$expire) : strtotime(
            date($format).$expire
        );
    }

    /**
     * @param $expire
     * @param string $format
     * @return string
     */
    public static function toDate($expire, string $format = 'Y:m:d H:i:s'): string
    {
        return date($format, $expire);
    }
}
