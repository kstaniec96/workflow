<?php
/**
 * Command create factory.
 */

namespace Simpler\Components\Database\Commands;

use Simpler\Components\Console\Command;

class CreateFactoryCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $this->stubGenerator('factory', basePath('db/factories'));

        return 0;
    }
}
