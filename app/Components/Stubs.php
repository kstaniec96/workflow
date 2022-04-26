<?php
/**
 * This class manages the so-called stubs - with class templates.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Facades\Dir;
use Simpler\Components\Facades\File;
use Simpler\Components\Interfaces\StubsInterface;
use Exception;
use RuntimeException;

class Stubs implements StubsInterface
{
    /** @var string|null */
    private static ?string $content = null;

    /**
     * @param string $stubTemplate
     * @return Stubs
     */
    public static function init(string $stubTemplate): Stubs
    {
        try {
            self::$content = File::content(STUBS_PATH.DS.$stubTemplate.'.stub');

            return new self();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @param string $className
     * @return Stubs
     */
    public function setClassName(string $className): Stubs
    {
        self::$content = str_replace('{{ class }}', self::getClassName($className), self::$content);

        return new self();
    }

    /**
     * @param string $namespace
     * @return Stubs
     */
    public function setNamespace(string $namespace): Stubs
    {
        self::$content = str_replace('{{ namespace }}', self::getNamespace($namespace), self::$content);

        return new self();
    }

    /**
     * @param string $use
     * @return Stubs
     */
    public function setUse(string $use): Stubs
    {
        self::$content = str_replace('{{ use }}', $use, self::$content);

        return new self();
    }

    /**
     * @param array $search
     * @param array $replace
     * @return Stubs
     */
    public function setCustom(array $search, array $replace): Stubs
    {
        self::$content = str_replace($search, $replace, self::$content);

        return new self();
    }

    /**
     * @param string $saveFileName
     * @return bool
     */
    public function save(string $saveFileName): bool
    {
        try {
            return File::put($saveFileName, self::$content);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Get namespace from class path.
     *
     * @param string $path
     * @return array|string|string[]
     */
    public static function getNamespace(string $path)
    {
        $namespace = str_replace(['/', '.'], ['\\', ''], Dir::getPathWithoutLast($path));

        if (!empty($namespace)) {
            $namespace = '\\'.$namespace;
        }

        return $namespace;
    }

    /**
     * Get class name from class path.
     *
     * @param string $path
     * @return string
     */
    public static function getClassName(string $path): string
    {
        return trim(Dir::basename($path), '\\');
    }
}
