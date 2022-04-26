<?php

declare(strict_types=1);

namespace Project\Providers;

use Simpler\Components\Http\Routers\Route;
use Simpler\Components\Provider;

class RouteProvider extends Provider
{
    public function handle(): void
    {
        /***** Import routes *****/
        Route::import('web.php');
        Route::import('api.php');

        /***** Render views *****/
        Route::render();

        /***** 404 - Not found *****/
        Route::routeNotFound('404.html');
    }
}
