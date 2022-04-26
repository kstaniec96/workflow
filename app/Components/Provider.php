<?php
/**
 * This class manages providers.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\ProviderInterface;

abstract class Provider implements ProviderInterface
{
    /**
     * Abstract handle method.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Register provider.
     *
     * @param string $provider
     * @return void
     */
    public function register(string $provider): void
    {
        container($provider)->call('handle');
    }
}
