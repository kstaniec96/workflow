<?php
/**
 * Modified and improved function to import any files embedded
 * on the server is a central class to import resources.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\ImportInterface;
use Simpler\Components\Security\Filter;
use RuntimeException;

class Import implements ImportInterface
{
    /**
     * Imports a specific file or group of files to the project.
     *
     * @param $path
     * @param bool $return
     * @param string|null $omission
     * @return mixed|null
     */
    public static function import($path, bool $return = false, string $omission = null)
    {
        /* Call to array $path argument. */
        if (is_array($path)) {
            foreach ($path as $file) {
                self::include($file);
            }
        } /* Call to string $path argument. */
        elseif (is_string($path)) {
            /* Call to normal structure for $path argument. */
            if (strpos($path, '*') === false) {
                if ($return) {
                    return self::include($path, $return);
                }

                self::include($path);
            } /* Call to glob structure for $path argument. */
            else {
                $path = self::path($path).DS.basename($path);

                if (!empty($omission)) {
                    foreach (glob($path) as $file) {
                        if (strpos($file, $omission) === false) {
                            self::include(basename($file));
                        }
                    }
                } else {
                    foreach (glob($path) as $file) {
                        self::include(basename($file));
                    }
                }
            }
        } /* Error: Different type of argument(s). */
        else {
            throw new RuntimeException(
                'Incompatible type <b>'.gettype($path).'</b> of the <u>Import::import</u> method argument(s)!'
            );
        }

        return null;
    }

    /**
     * Checks whether a path or file exists.
     *
     * @param string $path
     * @param bool $check
     * @return string
     */
    protected static function path(string $path, bool $check = false): string
    {
        // When will glob be used or
        // When to check only the dirname path.
        if ($check || strpos($path, '*') !== false) {
            return self::isPath($path);
        }

        // When checking the file or the path.
        return strpos($path, '.') !== false ? self::isFile($path) : self::isPath($path);
    }

    /**
     * Creates an absolute path to the file and
     * modifies it accordingly.
     *
     * @param string $path
     * @param bool $return
     * @return mixed
     */
    private static function include(string $path, bool $return = false)
    {
        $newPath = self::path($path);

        if ($return) {
            return include $newPath;
        }

        require $newPath;
    }

    /**
     * Checks whether a file exists.
     *
     * @param string $path
     * @return string
     */
    private static function isFile(string $path): string
    {
        $path = Filter::path($path);

        if (!file_exists($path)) {
            throw new RuntimeException($path.' file not found!');
        }

        return $path;
    }

    /**
     * Checks whether a path exists.
     *
     * @param string $path
     * @return string
     */
    private static function isPath(string $path): string
    {
        $path = Filter::path($path);

        if (!is_dir(dirname($path))) {
            throw new RuntimeException($path.' path not found!');
        }

        return $path;
    }
}
