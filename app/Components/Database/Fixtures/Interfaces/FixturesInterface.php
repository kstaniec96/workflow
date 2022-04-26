<?php

namespace Simpler\Components\Database\Fixtures\Interfaces;

interface FixturesInterface
{
    /**
     * @param string|null $fixtureName
     * @return string
     */
    public static function create(?string $fixtureName): string;

    /**
     * @param string|null $fixtureName
     * @return void
     */
    public static function run(?string $fixtureName): void;
}
