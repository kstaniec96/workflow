<?php

namespace Simpler\Components\Http\Providers\Interfaces;

interface SessionInterface
{
    /**
     * @param bool $regenerate
     * @return void
     */
    public function start(bool $regenerate = true): void;

    /**
     * @param bool $logout
     * @return bool
     */
    public function clear(bool $logout = false): bool;
}
