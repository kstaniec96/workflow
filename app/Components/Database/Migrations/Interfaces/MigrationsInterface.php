<?php

namespace Simpler\Components\Database\Migrations\Interfaces;

interface MigrationsInterface
{
    /**
     * @param string|null $migrationName
     * @return string
     */
    public static function create(?string $migrationName): string;

    /**
     * @param mixed $migration
     * @return void
     */
    public static function up($migration): void;

    /**
     * @param mixed $migration
     * @return void
     */
    public static function down($migration): void;
}
