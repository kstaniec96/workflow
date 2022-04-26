<?php
/**
 * Command create console command class.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateConsoleCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('command', COMMANDS_PATH);

        return 0;
    }
}
