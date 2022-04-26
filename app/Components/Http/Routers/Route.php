<?php
/**
 * This class of management Routing.
 *
 * @package Simpler
 * @subpackage Routers
 * @version 2.0
 */

namespace Simpler\Components\Http\Routers;

use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Exceptions\ResponseException;
use Simpler\Components\Http\Routers\Interfaces\RouteInterface;
use Simpler\Components\Localization;
use Simpler\Components\Security\Csrf;
use RuntimeException;

class Route implements RouteInterface
{
    /** @var string */
    private static string $as = '';

    /** @var array */
    private static array $middlewares = [
        'root' => [],
        'group' => [],
        'route' => [],
    ];

    /** @var string */
    private static string $prefix = '';

    /** @var bool */
    private static bool $exists = false;

    /** @var array */
    private static array $routes = [];

    /** @var string */
    private static string $url = '';

    /** @var array */
    private static array $params = [];

    /** @var mixed */
    private static $render;

    /** @var string|null */
    private static string $rootAs = '';

    /** @var string */
    private static string $rootPrefix = '';

    /** @var string */
    private static string $routeName = '';

    /** @var string */
    private static string $currentRouteName = '';

    /** @var string */
    private static string $currentRouteAction = '';

    /*
     * Routing for Web.
     */
    public static function web(array $params, $render): Route
    {
        return self::routing($params, $render);
    }

    /*
     * Routing for API.
     */
    public static function api(array $params, $render): Route
    {
        if (request()->isApi()) {
            Csrf::check();
        }

        return self::routing($params, $render, false);
    }

    /*
     * Init group views.
     */
    public static function group(array $params, $closure)
    {
        $prefix = self::routeHome($params['prefix'] ?? '');
        $as = $params['as'] ?? '';

        self::reset();

        if (compare(debug_backtrace(false, 2)[1]['function'], 'require')) {
            self::$rootPrefix = $prefix;
            self::$rootAs = $as;

            self::setMiddleware($params, 'root');
        } else {
            self::$prefix = $prefix;
            self::$as = $as;

            self::setMiddleware($params, 'group');
        }

        return $closure();
    }

    /**
     * Show 404 Not Found view.
     */
    public static function routeNotFound($arg = null)
    {
        if (!self::$exists || empty(self::$render)) {
            if (is_callable($arg)) {
                return $arg();
            }

            if (request()->isApi()) {
                throw new ResponseException('The given page has not been found', HttpStatus::NOT_FOUND);
            }

            abort(HttpStatus::NOT_FOUND);
        }

        return null;
    }

    /**
     * Get route url.
     *
     * @param string $route
     * @param array $params
     * @return mixed
     */
    public static function getRouteUrl(string $route, array $params = [])
    {
        $routes = self::$routes[$route] ?? null;

        if (empty($routes)) {
            throw new RuntimeException('Route name "'.$route.'" has not been defined!');
        }

        preg_match_all('/\{([^}]*)\}/', self::$routes[$route], $matches);

        $matches = $matches[1];
        $url = self::$routes[$route];

        if (!empty($matches)) {
            foreach ($matches as $match) {
                $param = $params[$match] ?? null;

                if (is_null($param)) {
                    throw new RuntimeException('Undefined param name "'.$match.'"');
                }

                $url = str_replace('{'.$match.'}', $param, $url);
                unset ($params[$match]);
            }
        }

        return url()->query()->build($params, $url);
    }

    /**
     * Get current route name.
     *
     * @return string
     */
    public static function getCurrentRouteName(): string
    {
        return self::$currentRouteName;
    }

    /**
     * Get current route action.
     *
     * @return string
     */
    public static function getCurrentRouteAction(): string
    {
        return self::$currentRouteAction;
    }

    /**
     * Render controller method or static function.
     *
     * @return void
     */
    public static function render(): void
    {
        self::initMiddlewares();

        if (is_array(self::$render)) {
            [$className, $classMethod] = self::$render;

            echo container($className)->call($classMethod, true, self::$params);
            exit;
        }

        if (is_callable(self::$render)) {
            echo (self::$render)(...self::$params);
        }
    }

    /**
     * Import files with routes.
     *
     * @param string $path
     * @return void
     */
    public static function import(string $path): void
    {
        import(basePath('routes/'.$path));
    }

    /*
     * Check HTTP method.
     */
    private static function checkMethod(string $method): void
    {
        if (!compare(request()->isMethod(), $method)) {
            throw new RuntimeException('Method not allowed!');
        }
    }

    /*
     * Init middlewares
     */
    private static function initMiddlewares(): void
    {
        if (!empty(self::$middlewares) && self::$exists) {
            $middlewares = array_merge(
                self::$middlewares['root'],
                self::$middlewares['group'],
                self::$middlewares['route']
            );

            foreach ($middlewares as $middleware) {
                echo container($middleware)->call('handle');
            }
        }
    }

    /**
     * Trait for routing API and Web.
     *
     * @param array $data
     * @param $render
     * @param bool $isWeb
     * @return Route
     */
    private static function routing(array $data, $render, bool $isWeb = true): Route
    {
        $uri = self::routeHome($data['uri'] ?? '');
        $uri = $isWeb ? self::removeFirstSlash(self::getPrefix().$uri) : 'api'.self::getPrefix().$uri;

        $pathname = url()->pathname();
        $params = self::params($pathname, $uri);

        self::$url = url()->join($uri);
        self::setName($data, $isWeb);

        if (compare($pathname, $uri)) {
            self::checkMethod($data['method'] ?? ($isWeb ? 'GET' : 'POST'));
            self::setMiddleware($data, 'route');

            self::$params = $params;
            self::$exists = true;
            self::$render = $render;
            self::$currentRouteName = self::getAs().self::$routeName;
            self::$currentRouteAction = is_callable($render) ? 'Closure' : self::splitRenderController();

            response()->setHeader('Current-Locale', Localization::getLocale());
        }

        return (new self());
    }

    /**
     * Split render controller.
     *
     * @return string
     */
    private static function splitRenderController(): string
    {
        [$className, $classMethod] = self::$render;

        return $className.'@'.$classMethod;
    }

    /**
     * @param array $params
     * @param string $index
     * @return void
     */
    private static function setMiddleware(array $params, string $index): void
    {
        if (!self::$exists) {
            self::$middlewares[$index] = $params['middlewares'] ?? [];
        }
    }

    /*
     * Remove first slash from string (uri).
     */
    private static function removeFirstSlash($str): string
    {
        return str_starts_with($str, '/') ? substr($str, 1) : $str;
    }

    /**
     * Get route home.
     */
    private static function routeHome(string $str): string
    {
        return (empty($str) || compare($str, '/')) ? '' : $str;
    }

    /**
     * @return string
     */
    private static function getPrefix(): string
    {
        return self::$rootPrefix.self::$prefix;
    }

    /**
     * @return string
     */
    private static function getAs(): string
    {
        return self::$rootAs.self::$as;
    }

    /**
     * @return void
     */
    private static function reset(): void
    {
        self::$prefix = '';
        self::$as = '';
    }

    /**
     * Set route name.
     *
     * @param array $data
     * @param bool $isWeb
     * @return void
     */
    private static function setName(array $data, bool $isWeb = true): void
    {
        self::$routeName = $data['name'];
        $name = self::getAs().self::$routeName;

        self::$routes[($isWeb ? '' : 'api.').$name] = self::$url;
    }

    /**
     * Get uri params.
     *
     * @param string $pathname
     * @param string $uri
     * @return array
     */
    private static function params(string &$pathname, string $uri): array
    {
        $explodePathname = explode('/', $pathname);
        $explodeUri = explode('/', $uri);

        $params = [];

        foreach ($explodeUri as $key => $item) {
            if (compare(preg_match('/\{([^}]*)\}/', $item), 1)) {
                $params[] = $explodePathname[$key] ?? '';
                $explodePathname[$key] = $item;
            }
        }

        $pathname = implode('/', $explodePathname);

        return $params;
    }
}
