<?php
/**
 * This class is used for date
 * and time operations
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\DateTimeInterface;
use Carbon\Carbon;
use Exception;
use RuntimeException;

class DateTime implements DateTimeInterface
{
    /**
     * Date time ago.
     *
     * @param mixed $date
     * @return string
     */
    public static function timeAgo($date): string
    {
        if (!is_null($date)) {
            return Carbon::parse(self::date($date))->diffForHumans();
        }

        return '-';
    }

    /**
     * Date to iso format.
     *
     * @param $date
     * @param string $format
     * @return string
     */
    public static function isoFormat($date, string $format = 'D MMM YY'): string
    {
        if (!is_null($date)) {
            return Carbon::parse(self::date($date))->isoFormat($format);
        }

        return '-';
    }

    /**
     * Add unit (hours, day, minutes etc.) to parse date.
     *
     * @param string $parse
     * @param mixed $value
     * @param string $unit
     * @param string|null $timezone
     * @return Carbon
     */
    public static function calc(string $parse, $value = 1, string $unit = 'hours', ?string $timezone = null): Carbon
    {
        try {
            return Carbon::parse($parse, $timezone)->modify($value.$unit);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Change date format.
     *
     * @param mixed $parse
     * @param string $format
     * @return mixed
     */
    public static function changeFormat($parse, string $format = 'Y-m-d H:i:s')
    {
        if (is_int($parse)) {
            $parse = date($format, $parse);
        }

        if (is_string($parse)) {
            return date($format, strtotime($parse));
        }

        return $parse;
    }

    /**
     * Join two or more dates.
     *
     * @param array $dates
     * @return string
     */
    public static function join(array $dates): string
    {
        return implode(' ', $dates);
    }

    /**
     * Check and get date.
     *
     * @param $date
     * @return string
     */
    private static function date($date): string
    {
        if (is_int($date)) {
            $date = self::changeFormat($date);
        }

        return str_replace('.', '-', $date);
    }
}
