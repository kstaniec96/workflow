<?php
/**
 * This class is helpers for string.
 *
 * @package Simpler
 * @subpackage Utils
 * @version 2.0
 */

namespace Simpler\Utils;

use Simpler\Utils\Interfaces\StringUtilInterface;
use Exception;
use Faker\Factory;
use RuntimeException;

class StringUtil implements StringUtilInterface
{
    /**
     * @param string $str
     * @return string
     */
    public static function strToUri(string $str): string
    {
        $search = [
            ' ',
            '!',
            '~',
            '@',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '_',
            '+',
            '=',
            ';',
            ',',
            '"',
            '\'',
            '{',
            '}',
            '[',
            ']',
            '|',
            '\\',
            '<',
            '>',
            '?',
            '.',
            '/',
            'ą',
            'ś',
            'ć',
            'ę',
            'ł',
            'ó',
            'ń',
            'ż',
            'ź',
            '#',
            ':',
        ];

        $replace = [
            '-',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'a',
            's',
            'c',
            'e',
            'l',
            'o',
            'n',
            'z',
            'z',
            '',
            '',
        ];

        $str = str_replace($search, $replace, mb_strtolower(trim($str)));

        return preg_replace('/(\-)+/', '-', $str);
    }

    /**
     * @param string $text
     * @return string
     */
    public static function clearMultiWhiteSpaces(string $text): string
    {
        return preg_replace('/\s+/', ' ', $text);
    }

    /**
     * @param string $content
     * @param bool $swapSpace
     * @return string
     */
    public static function removePolishChars(string $content, bool $swapSpace = false): string
    {
        $content = strip_tags($content);

        $search = ['ą', 'Ą', 'ę', 'Ę', 'ś', 'Ś', 'Ć', 'ć', 'Ń', 'ń', 'Ż', 'ż', 'Ź', 'ź', 'Ó', 'ó', 'ł', 'Ł'];
        $replace = ['a', 'A', 'e', 'E', 's', 'S', 'C', 'c', 'N', 'n', 'Z', 'z', 'Z', 'z', 'O', 'o', 'l', 'L'];

        if ($swapSpace) {
            $search[] = ' ';
            $replace[] = ' ';
        }

        return str_replace($search, $replace, trim($content));
    }

    /**
     * @param int $length
     * @return string
     */
    public static function random(int $length = 7): string
    {
        return Factory::create()->regexify('[0-9A-Za-z]{'.$length.'}');
    }

    /**
     * @param int $length
     * @return string
     */
    public static function randSecure(int $length = 32): string
    {
        try {
            return sha1(random_bytes($length));
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @param string $text
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function cutText(string $text, int $length = 100, string $pad = '...'): string
    {
        $text = self::removeNbsp($text);

        if (strlen($text) > $length) {
            return substr(strip_tags($text), 0, $length).'...';
        }

        return strip_tags($text);
    }

    /**
     * @param string $text
     * @return string
     */
    public static function removeNbsp(string $text): string
    {
        return str_replace('&nbsp;', '', $text);
    }

    /**
     * @param string $default
     * @param int $count
     * @param array $condition
     * @return string
     */
    public static function endOfWordWithCount(string $default, int $count, array $condition): string
    {
        foreach ($condition as $key => $value) {
            if (is_array($value) && in_array($count, $value, true)) {
                return $key;
            }
        }

        return $default;
    }

    /**
     * @param string $haystack
     * @param $needle
     * @param int $offset
     * @return false|int|string
     */
    public static function is(string $haystack, $needle, int $offset = 0)
    {
        if (is_array($needle)) {
            foreach ($needle as $key => $search) {
                $pos = strpos($haystack, $search, $offset);
                if ($pos !== false) {
                    return $key;
                }
            }

            return false;
        }

        return strpos($haystack, $needle, $offset);
    }

    /**
     * @param string $pattern
     * @param $subject
     * @return bool
     */
    public static function match(string $pattern, $subject): bool
    {
        if (is_array($subject)) {
            $exists = false;

            foreach ($subject as $key => $value) {
                if (preg_match($pattern, $key)) {
                    $exists = true;
                } else {
                    $exists = false;
                    break;
                }
            }

            return $exists;
        }

        return (bool)preg_match($pattern, $subject);
    }
}
