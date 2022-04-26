<?php

namespace Simpler\Components\Database\Migrations\Interfaces;

interface MigrationInterface
{
    /**
     * @param string $sql
     * @return void
     */
    public function run(string $sql): void;
}
