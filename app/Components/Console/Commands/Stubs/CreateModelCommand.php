<?php
/**
 * Command create model.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateModelCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('model', MODELS_PATH);

        return 0;
    }
}
