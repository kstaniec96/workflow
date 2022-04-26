<?php

declare(strict_types=1);

namespace Project\Providers;

use Simpler\Components\Provider;

class ProjectProviders extends Provider
{
    public function handle(): void
    {
        $this->register(RouteProvider::class);
    }
}
