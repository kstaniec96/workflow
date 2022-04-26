<?php

namespace Simpler\Components\Interfaces;

use Simpler\Components\ReflectionContainer;

interface ReflectionContainerInterface
{
    /**
     * @param string $class
     * @return object|ReflectionContainer
     */
    public static function instance(string $class);

    /**
     * @param string $methodName
     * @param bool $isDependencies
     * @param array $params
     * @return mixed
     */
    public function call(string $methodName, bool $isDependencies = false, array $params = []);

    /**
     * @return mixed
     */
    public function getInstance();
}
