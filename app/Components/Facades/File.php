<?php
/**
 * This class manages the file stream,
 * creates, deletes, updates and reads
 * the file from the file.
 *
 * @package Simpler
 * @subpackage Facades
 * @version 2.0
 */

namespace Simpler\Components\Facades;

use Simpler\Components\Facades\Interfaces\FileInterface;
use Simpler\Utils\StringUtil;
use RuntimeException;

class File implements FileInterface
{
    /**
     * Checks whether the file exists at all.
     *
     * @param string $path
     * @return bool
     */
    public static function has(string $path): bool
    {
        return file_exists(filterPath($path));
    }

    /**
     * Checks whether the file is empty.
     *
     * @param string $path
     * @return bool
     */
    public static function isEmpty(string $path): bool
    {
        return empty(trim(file_get_contents($path)));
    }

    /**
     * Creates a file and overwrites its contents
     * each time the method is called.
     *
     * @param string $path
     * @param mixed $content
     * @param bool $newLine
     * @return false|int
     */
    public static function put(string $path, $content = '', bool $newLine = true)
    {
        clearstatcache();
        Dir::create($path);

        return self::fileMethods($path, $content, $newLine);
    }

    /**
     * At the beginning of the file, add the content provided
     * as the method argument.
     *
     * @param string $path
     * @param mixed $content
     * @param bool $newLine
     * @return false|int
     */
    public static function prepend(string $path, $content, bool $newLine = true)
    {
        return self::fileMethods($path, $content, $newLine, true);
    }

    /**
     * At the end of the file, add the content provided
     * as the method argument.
     *
     * @param string $path
     * @param mixed $content
     * @param bool $newLine
     * @return false|int
     */
    public static function append(string $path, $content, bool $newLine = true)
    {
        return self::fileMethods($path, $content, $newLine, false, true);
    }

    /**
     * This is the trait for methods from
     * the create and update section.
     *
     * @param string $path
     * @param mixed $content
     * @param bool $newLine
     * @param bool $prepend
     * @param bool $append
     * @return false|int
     */
    private static function fileMethods(
        string $path,
        $content,
        bool $newLine = true,
        bool $prepend = false,
        bool $append = false
    ) {
        if (is_array($content)) {
            $content = implode($newLine ? PHP_EOL : '; ', $content);
        }

        // Create a prepend content.
        if ($prepend) {
            $content = $newLine && !self::isEmpty($path) ? $content.PHP_EOL.self::content($path).PHP_EOL :
                $content.self::content($path, false, false);
        } // Create a append content.
        elseif ($append) {
            $content = $newLine && !self::isEmpty($path) ? self::content($path).PHP_EOL.$content.PHP_EOL :
                self::content($path, false, false).$content;
        } // Create a normal content.
        else {
            $content = $newLine ? $content.PHP_EOL : $content;
        }

        // Adds the content to the file.
        return file_put_contents(filterPath($path), trim($content));
    }

    /**
     * It allows you to change the mode file:
     * - private (0666),
     * - public (0755),
     * - protected (0750)
     *
     * @param string $path
     * @param mixed $modifier
     * @return bool
     */
    public static function setAccess(string $path, $modifier = 'public'): bool
    {
        // Has the name method been called?
        $mod = 0755;

        switch ($modifier) {
            case 'private':
                $mod = 0666;
                break;
            case 'protected':
                $mod = 0750;
                break;
        }

        // Sets a new file permissions.
        return chmod(filterPath($path), $mod);
    }

    /**
     * Changes the file name to the new name.
     *
     * @param string $oldName
     * @param string $newName
     * @return bool
     */
    public static function rename(string $oldName, string $newName): bool
    {
        $oldName = filterPath($oldName);

        // Gets the name of the directory.
        $dir = dirname($oldName);

        // Get file extension.
        $extension = pathinfo(basename($oldName))['extension'];

        if (!self::has($oldName)) {
            return false;
        }

        // Modify filename
        return rename($oldName, $dir.DS.$newName.'.'.$extension);
    }

    /**
     * Moves the selected file or files to
     * the specified new path.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    public static function move(string $from, string $to): bool
    {
        return self::traitTransferMethods($from, $to, true);
    }

    /**
     * Copy the selected file or files to
     * the specified new path.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    public static function copy(string $from, string $to): bool
    {
        return self::traitTransferMethods($from, $to);
    }

    /**
     * Performs the operation of moving
     * or copying the selected file
     * to a new location.
     */
    private static function traitTransferMethods(string $from, string $to, bool $moved = false): bool
    {
        // Until the file exists.
        if (!self::has($to) || preg_match('/^(\.|\.\|\.\/)+/', $to)) {
            // Get content from file.
            $content = self::content($from);

            // Get mode file
            $mode = self::mode($from);

            // Remove old file.
            // Only when the file transfer operation is performed.
            if ($moved) {
                self::delete($from);
            }

            // Create new file
            self::put($to, $content);

            // Transfer of file rights.
            chmod($to, '0'.$mode);

            // Have you succeeded?
            return self::has($to);
        }

        return false;
    }

    /**
     * Reads the contents of the file given as
     * the method argument.
     *
     * @param string $path
     * @param bool $array
     * @param bool $new_line
     * @return array|false|string
     */
    public static function content(string $path, bool $array = false, bool $new_line = true)
    {
        $path = filterPath($path);

        // Is there a new line?
        if (!$array) {
            return $new_line ? file_get_contents($path) :
                StringUtil::clearMultiWhiteSpaces(file_get_contents($path));
        }

        // Return content as array.
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Gets the number of lines from
     * the file bypassing empty lines.
     *
     * @param string $path
     * @return int
     */
    public static function length(string $path): int
    {
        return count(self::content($path, true, false));
    }

    /**
     * Gets the number of chars in file.
     *
     * @param string $path
     * @return int
     */
    public static function chars(string $path): int
    {
        return strlen(self::content($path, false, false));
    }

    /**
     * Gets the size of the file given as an argument in the get() method.
     * By default, units using KB and MB are enabled.
     *
     * @param string $path
     * @param bool $units
     * @return false|int|string
     */
    public static function size(string $path, bool $units = true)
    {
        // What size is the file?
        $size = filesize(filterPath($path));

        if ($units) {
            if ($size >= 1048576) {
                $size = number_format($size / 1048576, 2).' MB';
            } elseif ($size >= 1024) {
                $size = number_format($size / 1024, 2).' KB';
            } elseif ($size > 1) {
                $size .= ' bytes';
            } elseif ($size === 1) {
                $size .= ' byte';
            } else {
                $size = '0 bytes';
            }
        }

        return $size;
    }

    /**
     * Gets the last modified time of the file given.
     *
     * @param string $path
     * @param string $format
     * @return string
     */
    public static function lastModified(string $path, string $format = 'Y:m:d H:i:s'): string
    {
        return date($format, filemtime(filterPath($path)));
    }

    /**
     * Gets the open/write mode of the file given.
     *
     * @param string $path
     * @param bool $retInt
     * @return string
     */
    public static function mode(string $path, bool $retInt = true): string
    {
        // What are the file permissions?
        $perms = fileperms(filterPath($path));

        if (!$retInt) {
            // Socket
            if (($perms & 0xC000) == 0xC000) {
                $stats = 's';
            } // Symbolic link
            elseif (($perms & 0xA000) == 0xA000) {
                $stats = 'l';
            } // Simple link
            elseif (($perms & 0xA000) == 0xA000) {
                $stats = 'l';
            } // Block device
            elseif (($perms & 0x6000) == 0x6000) {
                $stats = 'b';
            } // Catalog
            elseif (($perms & 0x4000) == 0x4000) {
                $stats = 'd';
            } // Character device
            elseif (($perms & 0x2000) == 0x2000) {
                $stats = 'c';
            } // FIFO
            elseif (($perms & 0x1000) == 0x1000) {
                $stats = 'p';
            } // Unknown
            else {
                $stats = 'u';
            }

            // Owner
            $stats .= (($perms & 0x0100) ? 'r' : '-');
            $stats .= (($perms & 0x0080) ? 'w' : '-');
            $stats .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

            // Group
            $stats .= (($perms & 0x0020) ? 'r' : '-');
            $stats .= (($perms & 0x0010) ? 'w' : '-');
            $stats .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

            // Others
            $stats .= (($perms & 0x0004) ? 'r' : '-');
            $stats .= (($perms & 0x0002) ? 'w' : '-');
            $stats .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

            // Returns the rights in the file in
            // the format -rw-r--r--.
            return $stats.' ('.decoct($perms & 0777).')';
        }

        // Only number
        return decoct($perms & 0777);
    }

    /**
     * This method returns bool or int type.
     * If the second argument is set, the method
     * returns true if it finds more than one phrase.
     *
     * @param string $path
     * @param string $phrase
     * @param bool $multi
     * @return array|false
     */
    public static function search(string $path, string $phrase, bool $multi = false)
    {
        return self::traitSearchMethods($path, $phrase, $multi);
    }

    /**
     * Displays the last line in which the searched
     * phrase is located.
     *
     * @param string $path
     * @param string $phrase
     * @return array|false
     */
    public static function searchLast(string $path, string $phrase)
    {
        return self::traitSearchMethods($path, $phrase, false);
    }

    /**
     * Displays all lines in which the searched
     * phrase is located.
     *
     * @param string $path
     * @param string $phrase
     * @return array|false
     */
    public static function searchAll(string $path, string $phrase)
    {
        return self::traitSearchMethods($path, $phrase, true);
    }

    /**
     * Checks whether the given line is in the file.
     *
     * @param string $path
     * @param int $lineNumber
     * @return string|false
     */
    public static function searchLine(string $path, int $lineNumber)
    {
        return self::traitSearchMethods($path, $lineNumber, false, true);
    }

    /**
     * This trait searches for the phrase or number
     * of line file given as the method parameter.
     *
     * @param string $path
     * @param mixed $search
     * @param bool $multi
     * @param bool $searchLine
     * @return array|bool|int|mixed|string
     */
    private static function traitSearchMethods(
        string $path,
        $search,
        bool $multi,
        bool $searchLine = false
    ) {
        $exists = false;

        // Gets the content of the file.
        $content = self::content($path, true, false);

        // Searching for values
        if (!$searchLine) {
            $found = [];

            // Searches the indicated file.
            $i = 0;
            $lastLine = 0;

            foreach ($content as $_line) {
                if (strpos($_line, $search) !== false) {
                    $found[$i] = $_line;
                    $exists = true;

                    $lastLine = $i;
                }

                $i++;
            }

            if ($exists) {
                if (!$multi) {
                    return [$lastLine => end($found)];
                }

                return count($found) > 1 ? $found : current($found);
            }

            return false;
        }

        // Line search
        return $content[$search] ?? false;
    }

    /**
     * Removes the specified file or all file.
     *
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool
    {
        return self::unlink($path);
    }

    /**
     * Removes the specified line from file.
     *
     * @param string $path
     * @param mixed $search
     * @return bool
     */
    public static function deleteLine(string $path, $search): bool
    {
        $line = $search;

        if (is_string($search)) {
            $line = self::searchLast($path, $search);

            if (!$line) {
                throw new RuntimeException('The line '.$search.' has not been found');
            }

            $line = key($line);
        }

        $content = self::content($path, true);

        if (is_null($content[$line] ?? null)) {
            throw new RuntimeException('The line '.$search.' has not been found');
        }

        unset($content[$line]);

        return (bool)self::put($path, $content);
    }

    /**
     * Removes the indicated duplicates from
     * the file except the last one or all.
     *
     * @param string $path
     * @param string $search
     * @return bool
     */
    public static function deleteDuplicatesLine(string $path, string $search): bool
    {
        // Has the name method been called?
        $lines = self::searchAll($path, $search);

        if ($lines) {
            $content = self::content($path, true, false);

            foreach ($lines as $number => $line) {
                unset($content[$number]);
            }

            return (bool)self::put($path, $content);
        }

        return false;
    }

    /**
     * It deletes the entire contents of the file,
     * but does not delete it.
     *
     * @param string $path
     * @return bool
     */
    public static function clear(string $path): bool
    {
        self::put($path);

        return self::isEmpty($path);
    }

    /**
     * Removes the specified file.
     *
     * @param string $path
     * @return bool
     */
    private static function unlink(string $path): bool
    {
        return unlink(filterPath($path));
    }
}
