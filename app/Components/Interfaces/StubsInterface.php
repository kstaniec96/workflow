<?php

namespace Simpler\Components\Interfaces;

use Simpler\Components\Stubs;

interface StubsInterface
{
    /**
     * @param string $stubTemplate
     * @return Stubs
     */
    public static function init(string $stubTemplate): Stubs;

    /**
     * @param string $className
     * @return Stubs
     */
    public function setClassName(string $className): Stubs;

    /**
     * @param string $namespace
     * @return Stubs
     */
    public function setNamespace(string $namespace): Stubs;

    /**
     * @param string $use
     * @return Stubs
     */
    public function setUse(string $use): Stubs;

    /**
     * @param array $search
     * @param array $replace
     * @return Stubs
     */
    public function setCustom(array $search, array $replace): Stubs;

    /**
     * @param string $saveFileName
     * @return bool
     */
    public function save(string $saveFileName): bool;

    /**
     * @param string $path
     * @return mixed
     */
    public static function getNamespace(string $path);

    /**
     * @param string $path
     * @return string
     */
    public static function getClassName(string $path): string;
}
