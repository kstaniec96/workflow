<?php

namespace Simpler\Components\Interfaces;

use Carbon\Carbon;

interface DateTimeInterface
{
    /**
     * @param mixed $date
     * @return string
     */
    public static function timeAgo($date): string;

    /**
     * @param mixed $date
     * @param string $format
     * @return string
     */
    public static function isoFormat($date, string $format = 'D MMM YY'): string;

    /**
     * @param string $parse
     * @param mixed $value
     * @param string $unit
     * @param string|null $timezone
     * @return Carbon
     */
    public static function calc(string $parse, $value = 1, string $unit = 'hours', ?string $timezone = null): Carbon;

    /**
     * @param $parse
     * @param string $format
     * @return mixed
     */
    public static function changeFormat($parse, string $format = 'Y-m-d');

    /**
     * @param array $dates
     * @return string
     */
    public static function join(array $dates): string;
}
