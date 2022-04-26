<?php
/**
 * Command create middleware class.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;

class CreateMiddlewareCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('middleware', MIDDLEWARES_PATH);

        return 0;
    }
}
