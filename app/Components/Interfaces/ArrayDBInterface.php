<?php

namespace Simpler\Components\Interfaces;

use Simpler\Components\ArrayDB;

interface ArrayDBInterface
{
    public static function where($columns = null, ?string $value = null): ArrayDB;

    public static function orderBy(string $column, ?string $ordering = null): ArrayDB;

    public function get(array $source_array, ?int $limit, string $source_columns): array;

    public function getOne(array $source_array, string $source_columns);

    public function getValue(array $source_array, string $column);
}
