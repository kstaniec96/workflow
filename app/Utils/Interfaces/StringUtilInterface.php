<?php

namespace Simpler\Utils\Interfaces;

interface StringUtilInterface
{
    /**
     * @param string $str
     * @return string
     */
    public static function strToUri(string $str): string;

    /**
     * @param string $text
     * @return string
     */
    public static function clearMultiWhiteSpaces(string $text): string;

    /**
     * @param string $content
     * @param bool $swapSpace
     * @return string
     */
    public static function removePolishChars(string $content, bool $swapSpace = false): string;

    /**
     * @param int $length
     * @return string
     */
    public static function random(int $length = 7): string;

    /**
     * @param int $length
     * @return string
     */
    public static function randSecure(int $length = 32): string;

    /**
     * @param string $text
     * @param int $length
     * @return string
     */
    public static function cutText(string $text, int $length = 100): string;

    /**
     * @param string $text
     * @return string
     */
    public static function removeNbsp(string $text): string;

    /**
     * @param string $default
     * @param int $count
     * @param array $condition
     * @return string
     */
    public static function endOfWordWithCount(string $default, int $count, array $condition): string;

    /**
     * @param string $haystack
     * @param $needle
     * @param int $offset
     * @return mixed
     */
    public static function is(string $haystack, $needle, int $offset = 0);

    /**
     * @param string $pattern
     * @param $subject
     * @return bool
     */
    public static function match(string $pattern, $subject): bool;
}
