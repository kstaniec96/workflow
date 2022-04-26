<?php
/**
 * Command create interface class.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateInterfaceCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('interface', INTERFACES_PATH);

        return 0;
    }
}
