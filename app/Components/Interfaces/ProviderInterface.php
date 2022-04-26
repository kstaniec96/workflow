<?php

namespace Simpler\Components\Interfaces;

interface ProviderInterface
{
    /**
     * @param string $provider
     * @return void
     */
    public function register(string $provider): void;
}
