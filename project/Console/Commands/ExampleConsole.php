<?php

declare(strict_types=1);

namespace Project\Console\Commands;

use Simpler\Components\Console\Console;

class ExampleConsole extends Console
{
    public function handle(): int
    {
        $this->info('Example info message');
        $this->warning('Example warning message');
        $this->success('Example success message');
        $this->error('Example error message');

        return 0;
    }
}
