<?php

namespace Simpler\Components\Http\Routers\Interfaces;

use Simpler\Components\Http\Routers\Route;

interface RouteInterface
{
    /**
     * @param array $params
     * @param $render
     * @return Route
     */
    public static function web(array $params, $render): Route;

    /**
     * @param array $params
     * @param $render
     * @return Route
     */
    public static function api(array $params, $render): Route;

    /**
     * @param array $params
     * @param $closure
     * @return mixed
     */
    public static function group(array $params, $closure);

    /**
     * @param $arg
     * @return mixed
     */
    public static function routeNotFound($arg = null);

    /**
     * @param string $route
     * @param array $params
     * @return mixed
     */
    public static function getRouteUrl(string $route, array $params = []);

    /**
     * @return string
     */
    public static function getCurrentRouteName(): string;

    /**
     * @return string
     */
    public static function getCurrentRouteAction(): string;

    /**
     * @return void
     */
    public static function render(): void;

    /**
     * @param string $path
     * @return void
     */
    public static function import(string $path): void;
}
