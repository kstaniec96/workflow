<?php

namespace Simpler\Components\Facades\Interfaces;

interface DirInterface
{
    /**
     * @param string $path
     * @return bool
     */
    public static function has(string $path): bool;

    /**
     * @param mixed $dir
     * @param int $access
     * @param bool $recursive
     * @return bool
     */
    public static function create($dir, int $access = 0777, bool $recursive = true): bool;

    /**
     * @param string $path
     * @param bool $root
     * @return bool
     */
    public static function clear(string $path, bool $root = false): bool;

    /**
     * @param string $path
     * @return array
     */
    public static function files(string $path): array;

    /**
     * @param string $path
     * @return string
     */
    public static function dirname(string $path): string;

    /**
     * @param string $path
     * @return string
     */
    public static function getPathWithoutLast(string $path): string;

    /**
     * @param string $path
     * @return string
     */
    public static function basename(string $path): string;

    /**
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool;

    /**
     * @param string $target
     * @param string $link
     * @return bool
     */
    public static function symlink(string $target, string $link): bool;
}
