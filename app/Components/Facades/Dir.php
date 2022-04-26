<?php
/**
 * This class manages the folders on the server,
 * creates, deletes, clears their contents.
 *
 * @package Simpler
 * @subpackage Facades
 * @version 2.0
 */

namespace Simpler\Components\Facades;

use Simpler\Components\Facades\Interfaces\DirInterface;
use Simpler\Utils\ArrayUtil;
use Simpler\Utils\TypeUtil;
use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

class Dir implements DirInterface
{
    /**
     * Checks whether the dir exists at all.
     *
     * @param string $path
     * @return bool
     */
    public static function has(string $path): bool
    {
        return is_dir(self::dirname($path));
    }

    /**
     * Create dir(s).
     *
     * @param mixed $dir
     * @param int $access
     * @param bool $recursive
     * @return bool
     */
    public static function create($dir, int $access = 0777, bool $recursive = true): bool
    {
        if (is_array($dir)) {
            foreach ($dir as $item) {
                $dirname = $item;

                if (is_array($item)) {
                    $access = ArrayUtil::searchKey(1, $item) ? $item[1] : 0777;
                    $recursive = ArrayUtil::searchKey(2, $item) ? $item[2] : true;

                    $dirname = $item[0];
                }

                $created = self::set($dirname, $access, $recursive);

                if (!$created) {
                    return false;
                }
            }

            return true;
        }

        return self::set($dir, $access, $recursive);
    }

    /**
     * Remove all dirs and files in custom path.
     *
     * @param string $path
     * @param bool $root
     * @return bool
     */
    public static function clear(string $path, bool $root = false): bool
    {
        $path = self::dirname($path);

        foreach (new DirectoryIterator($path) as $fileinfo) {
            $deleted = true;

            if ($fileinfo->isFile() || $fileinfo->isLink()) {
                $deleted = unlink($fileinfo->getPathName());
            } elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $deleted = self::delete(
                    str_replace('\\', DS, $path.DS.basename($fileinfo->getPathName()))
                );
            }

            if (!$deleted) {
                return false;
            }
        }

        if ($root) {
            return self::delete($path);
        }

        return true;
    }

    /**
     * Show files and dir list.
     *
     * @param string $path
     * @return array
     */
    public static function files(string $path): array
    {
        $path = self::dirname($path);

        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $files = [];

        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }

            $files[] = $file->getPathname();
        }

        return $files;
    }

    /**
     * Get only dirname from path.
     *
     * @param string $path
     * @return string
     */
    public static function dirname(string $path): string
    {
        $path = filterPath($path);

        if (TypeUtil::isFileName($path)) {
            $path = dirname($path);
        }

        return $path;
    }

    /**
     * Get path without last
     *
     * @param string $path
     * @return string
     */
    public static function getPathWithoutLast(string $path): string
    {
        return dirname(filterPath($path));
    }

    /**
     * Get basename path.
     *
     * @param string $path
     * @return string
     */
    public static function basename(string $path): string
    {
        return basename(filterPath($path));
    }

    /**
     * Remove custom dir
     *
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool
    {
        self::clear($path);

        return rmdir(self::dirname($path));
    }

    /**
     * Creates a symbolic link
     *
     * @param string $target
     * @param string $link
     * @return bool
     */
    public static function symlink(string $target, string $link): bool
    {
        if (!self::has($target)) {
            throw new RuntimeException('Target directory does not exist');
        }

        if (self::has($link)) {
            throw new RuntimeException('Symlink has already been created');
        }

        return symlink(filterPath($target), filterPath($link));
    }

    /**
     * Set a new dir
     *
     * @param string $path
     * @param int $access
     * @param bool $recursive
     * @return bool
     */
    private static function set(string $path, int $access, bool $recursive = true): bool
    {
        if (!self::has($path) && !mkdir($concurrentDirectory = self::dirname($path), $access, $recursive) &&
            !is_dir($concurrentDirectory)
        ) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        return self::has($path);
    }
}
