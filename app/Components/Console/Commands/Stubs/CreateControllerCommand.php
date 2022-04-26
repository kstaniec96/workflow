<?php
/**
 * Command create controller.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateControllerCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('controller', CONTROLLERS_PATH);

        return 0;
    }
}
