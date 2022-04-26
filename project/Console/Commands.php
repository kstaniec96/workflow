<?php

declare(strict_types=1);

namespace Project\Console;

use Simpler\Components\Console\Console;
use Project\Console\Commands\ExampleConsole;

class Commands extends Console
{
    protected array $commands = [
        'example-command' => ExampleConsole::class,
    ];
}
