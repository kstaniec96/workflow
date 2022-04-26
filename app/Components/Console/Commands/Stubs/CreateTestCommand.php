<?php
/**
 * Command create test class.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateTestCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $type = $this->getOptions('type') ?? 'unit';
        $type = ucfirst($type);
        $argv = $type.'\\'.$this->getArgv(0);

        $this->stubGenerator('test', TEST_PATH, $argv);

        return 0;
    }
}
