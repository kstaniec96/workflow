<?php
/**
 * This class is used to Reflection class Container.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Interfaces\ReflectionContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ReflectionContainer implements ReflectionContainerInterface
{
    /** @var mixed */
    private static $instance;

    /**
     * Resolve class dependency injection.
     *
     * @param string $class
     * @return object|ReflectionContainer
     */
    public static function instance(string $class)
    {
        try {
            $reflectionClass = new ReflectionClass($class);

            if (($constructor = $reflectionClass->getConstructor()) === null) {
                self::$instance = $reflectionClass->newInstance();

                return new self();
            }

            if (($params = $constructor->getParameters()) === []) {
                self::$instance = $reflectionClass->newInstance();

                return new self();
            }

            $newInstanceParams = [];

            foreach ($params as $param) {
                $className = $param->getType() && !$param->getType()->isBuiltin() ? $param->getType()->getName() : null;
                $newInstanceParams[] = is_null($className) ? $param->getDefaultValue() : self::instance($className);
            }

            self::$instance = $reflectionClass->newInstanceArgs(
                $newInstanceParams
            );

            return new self();
        } catch (ReflectionException $e) {
            throw new ThrowException($e);
        }
    }

    /**
     * Resolve method dependency injection.
     *
     * @param string $methodName
     * @param bool $isDependencies
     * @param array $params
     * @return mixed
     */
    public function call(string $methodName, bool $isDependencies = false, array $params = [])
    {
        try {
            $dependencies = [];

            if ($isDependencies) {
                $method = new ReflectionMethod(self::$instance, $methodName);
                $parameters = $method->getParameters();

                foreach ($parameters as $param) {
                    $dependenceClass = $param->getType() ? $param->getType()->getName() : null;

                    if (!is_null($dependenceClass) && class_exists($dependenceClass)) {
                        $dependencies[] = new $dependenceClass();
                    }
                }
            }

            $parameters = array_merge($params, $dependencies);

            return self::$instance->$methodName(...$parameters);
        } catch (ReflectionException $e) {
            throw new ThrowException($e);
        }
    }

    /**
     * Get Reflection class instance.
     *
     * @return mixed
     */
    public function getInstance()
    {
        return self::$instance;
    }
}
