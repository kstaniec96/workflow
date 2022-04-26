<?php
/**
 * This file is abstract class for Factory class.
 *
 * @package Simpler
 * @subpackage Factories
 * @version 2.0
 */

namespace Simpler\Components\Database\Factories;

abstract class AbstractFactory
{
    /**
     * @return array
     */
    abstract public function handle(): array;
}
