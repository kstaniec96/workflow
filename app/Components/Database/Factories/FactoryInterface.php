<?php

namespace Simpler\Components\Database\Factories;

interface FactoryInterface
{
    /**
     * @param string $factory
     * @return array
     */
    public static function make(string $factory): array;
}
