<?php
/**
 * The command creates a migration.
 */

namespace Simpler\Components\Database\Commands\Migration;

use Simpler\Components\Console\Command;
use Simpler\Components\Database\Migrations\Migrations;
use Exception;

class MigrationCreateCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            $migration = Migrations::create($this->getArgv()[0] ?? null);
            $this->success('Migration '.$migration.' has been created!');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
