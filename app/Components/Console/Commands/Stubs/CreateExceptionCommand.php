<?php
/**
 * Command create exception.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateExceptionCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('exception', EXCEPTIONS_PATH);

        return 0;
    }
}
