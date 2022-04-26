<?php
/**
 * Command create validator class.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateValidatorCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('validator', VALIDATORS_PATH);

        return 0;
    }
}
