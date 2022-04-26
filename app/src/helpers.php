<?php
/**
 * This file contains all additional functions.
 *
 * @package Simpler
 * @version 2.0
 */

use Simpler\Components\Auth\Auth;
use Simpler\Components\Config;
use Simpler\Components\Database\Factories\Factory;
use Simpler\Components\Exceptions\HandlerException;
use Simpler\Components\Facades\File;
use Simpler\Components\Http\Providers\Cookie;
use Simpler\Components\Http\Providers\Session;
use Simpler\Components\Http\Routers\Route;
use Simpler\Components\Http\Url\Url;
use Simpler\Components\Import;
use Simpler\Components\Localization;
use Simpler\Components\Mailer;
use Simpler\Components\ReflectionContainer;
use Simpler\Components\Requests\Redirect;
use Simpler\Components\Requests\Request;
use Simpler\Components\Response\Response;
use Simpler\Components\Security\CSP;
use Simpler\Components\Security\Csrf;
use Simpler\Components\Security\Filter;
use Carbon\Carbon;
use Monolog\Logger;

if (!function_exists('uploads')) {
    /**
     * Get URL to upload files.
     *
     * @param string $path
     * @return string
     */
    function uploads(string $path): string
    {
        return url()->join('uploads/'.$path);
    }
}

if (!function_exists('version')) {
    /**
     * Added version to asset URL file.
     *
     * @param string $route
     * @param bool $filemtime
     * @return string
     */
    function version(string $route, bool $filemtime = true): string
    {
        $url = asset($route);

        return $filemtime ? $url.'?v='.filemtime(ASSETS_PATH.DS.$route) : $url;
    }
}

if (!function_exists('asset')) {
    /**
     * Get full URL to asset file.
     *
     * @param string $path
     * @return string
     */
    function asset(string $path): string
    {
        return url()->join('assets/'.$path);
    }
}

if (!function_exists('__')) {
    /**
     * Translate content
     *
     * @param string $index
     * @param array $bind
     * @param bool $nullable
     * @return null|string
     */
    function __(string $index, array $bind = [], bool $nullable = false): ?string
    {
        return Localization::lang($index, $bind, $nullable);
    }
}

if (!function_exists('mailer')) {
    /**
     * Send email message.
     *
     * @param array $params
     * @return bool
     */
    function mailer(array $params): bool
    {
        return (new Mailer())->send($params);
    }
}

if (!function_exists('nonce')) {
    /**
     * Get CSP nonce.
     *
     * @return string
     */
    function nonce(): string
    {
        try {
            return CSP::nonce();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}

if (!function_exists('import')) {
    /**
     * Imports a specific file or group of files to the project.
     *
     * @param $path
     * @param bool $return
     * @param string|null $omission
     * @return mixed|null
     */
    function import($path, bool $return = false, string $omission = null)
    {
        return Import::import($path, $return, $omission);
    }
}

if (!function_exists('filterPath')) {
    /**
     * Filters the path to the file given as the method argument.
     *
     * @param string $path
     * @return string
     */
    function filterPath(string $path): string
    {
        return Filter::path($path);
    }
}

if (!function_exists('progress')) {
    /**
     * Progress info.
     *
     * @param int $total_score
     * @param int $max_score
     * @return int
     */
    function progress(int $total_score, int $max_score): int
    {
        $progress = $total_score > 0 && $max_score > 0 ? number_format(($total_score / $max_score) * 100, 0) : 0;

        return $progress >= 100 ? 100 : (int)$progress;
    }
}

if (!function_exists('route')) {
    /**
     * Get route URL.
     *
     * @param null|string $route
     * @param array $params
     * @return string
     */
    function route(?string $route = null, array $params = []): string
    {
        return $route ? Route::getRouteUrl($route, $params) : Route::getCurrentRouteName();
    }
}

if (!function_exists('hasRoute')) {
    /**
     * Has route name.
     *
     * @param string $route
     * @return bool
     */
    function hasRoute(string $route): bool
    {
        return compare(getCurrentRouteName(), $route);
    }
}

if (!function_exists('getCurrentRouteName')) {
    /**
     * Get current route name.
     *
     * @return string
     */
    function getCurrentRouteName(): string
    {
        return Route::getCurrentRouteName();
    }
}

if (!function_exists('getCurrentRouteAction')) {
    /**
     * Get current route name.
     *
     * @return string
     */
    function getCurrentRouteAction(): string
    {
        return Route::getCurrentRouteAction();
    }
}

if (!function_exists('compare')) {
    /**
     * Compare two vars.
     *
     * @param $a
     * @param $b
     * @return bool
     */
    function compare($a, $b): bool
    {
        return $a == $b;
    }
}

if (!function_exists('config')) {
    /**
     * Get config value from key.
     *
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    function config(string $key, string $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('isConsole')) {
    /**
     * Check request for console.
     *
     * @return bool
     */
    function isConsole(): bool
    {
        return defined('RUN_CONSOLE') && RUN_CONSOLE;
    }
}

if (!function_exists('env')) {
    /**
     * @param string $name
     * @param string|null $default
     * @return bool|mixed|string|null
     */
    function env(string $name, ?string $default = null)
    {
        $env = $_ENV[$name] ?? null;
        $env = $env ?? $default;

        if (compare($env, 'true')) {
            return true;
        }

        if (compare($env, 'false')) {
            return false;
        }

        return $env;
    }
}

if (!function_exists('dv')) {
    /**
     * @param $arg
     * @return void
     */
    function dv($arg): void
    {
        die(var_export($arg));
    }
}

if (!function_exists('csrfToken')) {
    /**
     * @return string
     */
    function csrfToken(): string
    {
        return Csrf::getToken();
    }
}

if (!function_exists('basePath')) {
    /**
     * Get base path.
     *
     * @param string $path
     * @return string
     */
    function basePath(string $path): string
    {
        return filterPath(ROOT_PATH.DS.$path);
    }
}

if (!function_exists('storagePath')) {
    /**
     * Get storage path.
     *
     * @param string $path
     * @return string
     */
    function storagePath(string $path): string
    {
        return filterPath(STORAGE_PATH.DS.$path);
    }
}

if (!function_exists('projectPath')) {
    /**
     * Get project path.
     *
     * @param string $path
     * @return string
     */
    function projectPath(string $path): string
    {
        return filterPath(PROJECT_PATH.DS.$path);
    }
}

if (!function_exists('usersPath')) {
    /**
     * Get path to users storage.
     *
     * @param string $path
     * @return string
     */
    function usersPath(string $path): string
    {
        return storagePath('project/users/'.$path);
    }
}

if (!function_exists('auth')) {
    /**
     * Check user is logged in.
     *
     * @return bool
     */
    function auth(): bool
    {
        return Auth::check();
    }
}

if (!function_exists('guest')) {
    /**
     * Check user is not logged in.
     *
     * @return bool
     */
    function guest(): bool
    {
        return Auth::guest();
    }
}

if (!function_exists('locale')) {
    /**
     * Get or set current locale name.
     *
     * @return string|bool
     */
    function locale(?string $locale = null)
    {
        return $locale ? Localization::setLocale($locale) : Localization::getLocale();
    }
}

if (!function_exists('timezone')) {
    /**
     * Get or set current timezone.
     *
     * @param string|null $timezone
     * @return string|bool
     */
    function timezone(?string $timezone = null)
    {
        return $timezone ? Localization::setTimezone($timezone) : Localization::getTimezone();
    }
}

if (!function_exists('container')) {
    /**
     * Get object instance class with call method.
     *
     * @param string $className
     * @return ReflectionContainer
     */
    function container(string $className): ReflectionContainer
    {
        return ReflectionContainer::instance($className);
    }
}

if (!function_exists('instance')) {
    /**
     * Get object instance class.
     *
     * @param string $className
     * @return mixed
     */
    function instance(string $className)
    {
        return container($className)->getInstance();
    }
}

if (!function_exists('logger')) {
    /**
     * Log errors
     *
     * @param mixed $message
     * @param mixed $level
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    function logger($message, $level = Logger::ERROR, array $context = [], ?string $channel = null): void
    {
        HandlerException::logger($message, $level, $context, $channel);
    }
}

if (!function_exists('abort')) {
    /**
     * Breaks the script and displays an error.
     *
     * @param int $code
     * @return void
     */
    function abort(int $code): void
    {
        HandlerException::abort($code);
    }
}

if (!function_exists('redirect')) {
    /**
     * Instance of redirect class.
     *
     * @return Redirect
     */
    function redirect(): Redirect
    {
        return new Redirect();
    }
}

if (!function_exists('request')) {
    /**
     * Instance of request class.
     *
     * @return Request
     */
    function request(): Request
    {
        return new Request();
    }
}

if (!function_exists('response')) {
    /**
     * Instance of request class.
     *
     * @return Response
     */
    function response(): Response
    {
        return new Response();
    }
}

if (!function_exists('url')) {
    /**
     * Instance of url class.
     *
     * @return Url
     */
    function url(): Url
    {
        return new Url();
    }
}

if (!function_exists('session')) {
    /**
     * Instance of session class or get session data.
     *
     * @param null|string $session
     * @param string|null $pattern
     * @return Session|string
     */
    function session(?string $session = null, ?string $pattern = null)
    {
        $instance = new Session();

        if (empty($session)) {
            return $instance;
        }

        return $instance->get($session, $pattern);
    }
}

if (!function_exists('cookie')) {
    /**
     * Instance of cookie class or get cookie data.
     *
     * @param null|string $cookie
     * @param string|null $pattern
     * @return Cookie|string
     */
    function cookie(string $cookie = null, ?string $pattern = null)
    {
        $instance = new Cookie();

        if (empty($cookie)) {
            return $instance;
        }

        return $instance->get($cookie, $pattern);
    }
}

if (!function_exists('now')) {
    /**
     * Breaks the script and displays an error.
     *
     * @param string|null $timezone
     * @return Carbon
     */
    function now(?string $timezone = null): Carbon
    {
        return Carbon::now($timezone);
    }
}

if (!function_exists('factory')) {
    /**
     * Get object instance class with call method.
     *
     * @param string $factory
     * @return array
     */
    function factory(string $factory): array
    {
        return Factory::make($factory);
    }
}

if (!function_exists('projectKey')) {
    /**
     * Get secret project key.
     *
     * @return string
     */
    function projectKey(): string
    {
        return File::content(storagePath('project-secret.key'));
    }
}

if (!function_exists('storage')) {
    /**
     * Get link to storage users path.
     *
     * @param string $path
     * @return string
     */
    function storage(string $path): string
    {
        return url()->join('storage/users/'.$path);
    }
}
