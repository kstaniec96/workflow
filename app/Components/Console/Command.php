<?php

namespace Simpler\Components\Console;

abstract class Command extends Console
{
    /**
     * Abstract handle method.
     *
     * @return int
     */
    abstract public function handle(): int;
}
