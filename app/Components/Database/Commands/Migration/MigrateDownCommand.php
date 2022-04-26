<?php
/**
 * Command to undo the migration.
 */

namespace Simpler\Components\Database\Commands\Migration;

use Simpler\Components\Console\Command;
use Simpler\Components\Database\Migrations\Migrations;
use Exception;

class MigrateDownCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            $migrations = Migrations::get(true, $this->getOptions('migration'));

            if (!empty($migrations)) {
                foreach ($migrations as $migration) {
                    Migrations::down($migration);
                    $this->success('Migration down '.$migration->name.' was successful!');
                }
            } else {
                $this->warning('Nothing to down migrate!');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
