<?php
/**
 * Command to push the fixture to the database.
 */

namespace Simpler\Components\Database\Commands\Fixture;

use Simpler\Components\Console\Command;
use Simpler\Components\Database\Fixtures\Fixtures;
use Exception;

class FixtureRunCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            $fixtures = Fixtures::get($this->getOptions('fixture'));

            if (!empty($fixtures)) {
                foreach ($fixtures as $fixture) {
                    $fixtureName = $fixture->name;

                    Fixtures::run($fixtureName);
                    $this->success('Fixture '.$fixtureName.' was successful!');
                }
            } else {
                $this->warning('Nothing to run fixture!');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
