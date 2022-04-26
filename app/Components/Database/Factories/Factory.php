<?php
/**
 * This class is managed for Factory data.
 *
 * @package Simpler
 * @subpackage Factories
 * @version 2.0
 */

namespace Simpler\Components\Database\Factories;

class Factory implements FactoryInterface
{
    /**
     * Make factory class data.
     *
     * @param string $factory
     * @return array
     */
    public static function make(string $factory): array
    {
        return container($factory)->call('handle');
    }
}
