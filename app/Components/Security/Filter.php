<?php

namespace Simpler\Components\Security;

use Simpler\Components\Security\Interfaces\FilterInterface;

class Filter extends XSSClean implements FilterInterface
{
    /**
     * Filters the URL address.
     *
     * @param string $input
     * @param bool $strip
     * @return string
     */
    public static function url(string $input, bool $strip = true): string
    {
        $input = str_replace(array('\\', ' '), array('/', ''), trim($input));

        if (compare($input, '/')) {
            return $input;
        }

        // add more chars if needed
        $input = str_ireplace(["\0", '%00', "\x0a", '%0a', "\x1a", '%1a'], '', rawurldecode($input));

        // remove markup stuff
        if ($strip) {
            $input = strip_tags($input);
        }

        // or any encoding you use instead of utf-8
        $input = htmlspecialchars($input, ENT_QUOTES, 'utf-8');

        // removes duplicates '/'
        return preg_replace('/([^:])(\/{2,})/', '$1/', $input);
    }

    /**
     * Filtrates (clears) the field
     * from the wrong characters.
     *
     * @param mixed $values
     * @param bool $trim
     * @return array|string|null
     */
    public static function clear($values, bool $trim = true)
    {
        if (is_null($values)) {
            return null;
        }

        if (is_array($values)) {
            $result = [];

            foreach ($values as $v) {
                $result[] = self::filterMethod($v, $trim);
            }

            return $result;
        }

        return self::filterMethod($values, $trim);
    }

    /**
     * Filters the path to the file given as the method argument.
     *
     * @param string $path
     * @return string
     */
    public static function path(string $path): string
    {
        $path = str_replace(array('./', '.\/'), '', $path);
        $path = strip_tags($path);
        $path = preg_replace('#(/|\/)+#', DS, trim($path));

        return str_replace(array('/', '\\'), DS, $path);
    }

    /**
     * Trait for the filtering method.
     *
     * @param mixed $value
     * @param bool $trim
     * @return string
     */
    private static function filterMethod($value, bool $trim = true): string
    {
        $filter = stripslashes(htmlspecialchars($value, ENT_QUOTES, 'utf-8'));

        return $trim ? self::secure(trim(preg_replace('/ {2,}/', ' ', $filter))) : self::secure(
            trim(preg_replace('/ {1,}/', '', $filter))
        );
    }
}
