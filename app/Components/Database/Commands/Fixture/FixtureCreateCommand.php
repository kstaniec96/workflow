<?php
/**
 * The command creates a fixture.
 */

namespace Simpler\Components\Database\Commands\Fixture;

use Simpler\Components\Console\Command;
use Simpler\Components\Database\Fixtures\Fixtures;
use Exception;

class FixtureCreateCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            $fixture = Fixtures::create($this->getArgv()[0] ?? null);
            $this->success('Fixture '.$fixture.' has been created!');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
