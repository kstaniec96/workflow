<?php
/**
 * Command to push the migration to the database.
 */

namespace Simpler\Components\Database\Commands\Migration;

use Simpler\Components\Console\Command;
use Simpler\Components\Database\Migrations\Migrations;
use Exception;

class MigrateUpCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            $migrations = Migrations::get(false, $this->getOptions('migration'));

            if (!empty($migrations)) {
                foreach ($migrations as $migration) {
                    Migrations::up($migration);
                    $this->success('Migration '.$migration->name.' was successful!');
                }
            } else {
                $this->warning('Nothing to up migrate!');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
