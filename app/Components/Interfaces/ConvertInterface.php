<?php

namespace Simpler\Components\Interfaces;

interface ConvertInterface
{
    /**
     * @param string $path
     * @return string
     */
    public static function toURL(string $path): string;

    /**
     * @param string $url
     * @return string
     */
    public static function toServerPath(string $url): string;

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
    ): int;

    /**
     * @param $expire
     * @param string $format
     * @return string
     */
    public static function toDate($expire, string $format = 'Y:m:d H:i:s'): string;
}
