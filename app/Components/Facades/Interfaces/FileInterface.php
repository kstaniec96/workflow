<?php

namespace Simpler\Components\Facades\Interfaces;

interface FileInterface
{
    /**
     * @param string $path
     * @return mixed
     */
    public static function has(string $path): bool;

    /**
     * @param string $path
     * @return bool
     */
    public static function isEmpty(string $path): bool;

    /**
     * @param string $path
     * @param mixed $content
     * @return false|int
     */
    public static function put(string $path, $content = '');

    /**
     * @param string $path
     * @param mixed $content
     * @return false|int
     */
    public static function prepend(string $path, $content);

    /**
     * @param string $path
     * @param mixed $content
     * @return false|int
     */
    public static function append(string $path, $content);

    /**
     * @param string $path
     * @param mixed $modifier
     * @return bool
     */
    public static function setAccess(string $path, $modifier = 'public'): bool;

    /**
     * @param string $oldName
     * @param string $newName
     * @return bool
     */
    public static function rename(string $oldName, string $newName): bool;

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public static function move(string $from, string $to): bool;

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public static function copy(string $from, string $to): bool;

    /**
     * @param string $path
     * @param bool $array
     * @param bool $new_line
     * @return mixed
     */
    public static function content(string $path, bool $array = false, bool $new_line = true);

    /**
     * @param string $path
     * @return int
     */
    public static function length(string $path): int;

    /**
     * @param string $path
     * @return int
     */
    public static function chars(string $path): int;

    /**
     * @param string $path
     * @param bool $units
     * @return mixed
     */
    public static function size(string $path, bool $units = true);

    /**
     * @param string $path
     * @param string $format
     * @return string
     */
    public static function lastModified(string $path, string $format = 'Y:m:d H:i:s'): string;

    /**
     * @param string $path
     * @param bool $retInt
     * @return string
     */
    public static function mode(string $path, bool $retInt = true): string;

    /**
     * @param string $path
     * @param string $phrase
     * @param bool $multi
     * @return array|false
     */
    public static function search(string $path, string $phrase, bool $multi = false);

    /**
     * @param string $path
     * @param string $phrase
     * @return array|false
     */
    public static function searchLast(string $path, string $phrase);

    /**
     * @param string $path
     * @param string $phrase
     * @return array|false
     */
    public static function searchAll(string $path, string $phrase);

    /**
     * @param string $path
     * @param int $lineNumber
     * @return string|false
     */
    public static function searchLine(string $path, int $lineNumber);

    /**
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool;

    /**
     * @param string $path
     * @param $search
     * @return bool
     */
    public static function deleteLine(string $path, $search): bool;

    /**
     * @param string $path
     * @param string $search
     * @return bool
     */
    public static function deleteDuplicatesLine(string $path, string $search): bool;

    /**
     * @param string $path
     * @return bool
     */
    public static function clear(string $path): bool;
}
