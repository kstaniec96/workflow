<?php

namespace Simpler\Utils\Interfaces;

interface TypeUtilInterface
{
    /**
     * @param string $value
     * @return bool
     */
    public static function isFileName(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isEmail(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isMd5(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isIP(string $value): bool;

    /**
     * @param $value
     * @return bool
     */
    public static function isUrl($value): bool;

    /**
     * @param string $regon
     * @return bool
     */
    public static function isREGON(string $regon): bool;

    /**
     * @param int $value
     * @return bool
     */
    public static function isAreaCode(int $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isNIP(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isIBAN(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isDateFormat(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isTimeFormat(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isDateTimeFormat(string $value): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isRegex(string $value): bool;

    /**
     * @param $string
     * @return bool
     */
    public static function isJson($string): bool;

    /**
     * @param string $file
     * @return bool
     */
    public static function isImage(string $file): bool;
}
