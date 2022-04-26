<?php

namespace Simpler\Components\Interfaces;

interface RandvalInterface
{
    public static function generate(int $quantity, string $range = 'mixed'): string;

    public static function bytes(int $quantity): string;

    public static function unique(int $min, int $max, int $quantity): array;
}
