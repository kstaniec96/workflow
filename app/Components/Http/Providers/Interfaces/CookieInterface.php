<?php

namespace Simpler\Components\Http\Providers\Interfaces;

interface CookieInterface
{
    /**
     * @return bool
     */
    public function clear(): bool;
}
