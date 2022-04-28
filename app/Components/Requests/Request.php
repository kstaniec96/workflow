<?php
/**
 * This class is used to handle everything
 * that is related to the request.
 *
 * @package Simpler
 * @subpackage Requests
 * @version 2.0
 */

namespace Simpler\Components\Requests;

use Simpler\Components\Config;
use Simpler\Components\DotNotation;
use Simpler\Components\Requests\Interfaces\RequestInterface;
use Simpler\Components\Security\Filter;
use Simpler\Components\Security\XSSClean;

class Request extends XSSClean implements RequestInterface
{
    /**
     * Checks whether the request was sent
     * using the POST method.
     *
     * @param bool $only_data_sent
     * @return bool
     */
    public function isPost(bool $only_data_sent = false): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) || ($only_data_sent && !empty($_POST));
    }

    /**
     * Checks whether the request was sent
     * using the GET method.
     *
     * @param bool $only_data_sent
     * @return bool
     */
    public function isGet(bool $only_data_sent = false): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) || ($only_data_sent && !empty($_GET));
    }

    /**
     * Checks whether the request was sent
     * using the PUT method.
     *
     * @param bool $only_data_sent
     * @return bool
     */
    public function isPut(bool $only_data_sent = false): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'PUT' && !empty($_PUT)) || ($only_data_sent && !empty($_PUT));
    }

    /**
     * Checks whether the request was sent
     * using the AJAX method.
     *
     * @return bool
     */
    public function isApi(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(
                    $_SERVER['HTTP_X_REQUESTED_WITH']
                ) === 'xmlhttprequest') || str_starts_with(url()->pathname(), 'api');
    }

    /**
     * Checks whether the request was sent
     * using the ALL methods.
     *
     * @return string
     */
    public function isMethod(): string
    {
        $method = Filter::clear($_SERVER['REQUEST_METHOD']);

        return strtoupper($method);
    }

    /**
     * checks the method and cleans
     * the appropriate global method array.
     *
     * @param string $key
     * @param bool $option
     * @return array|int|string|null
     */
    public function get(string $key, bool $option = true)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->fromGET($key, $option);
            case 'POST':
                return $this->fromPOST($key, $option);
            default:
                return Filter::clear($_REQUEST[$key]);
        }
    }

    /**
     * Get all data from request.
     *
     * @return array
     */
    public function all(): array
    {
        $results = [];

        if ($_REQUEST) {
            foreach ($_REQUEST as $key => $value) {
                $results[$key] = Filter::clear($value);
            }
        }

        return $results;
    }

    /**
     * Bind an event handler to the submit
     * event form.
     *
     * @param string $button
     * @param $function
     * @return mixed
     */
    public function submit(string $button, $function)
    {
        $request = $this->all();

        // Dot notation use
        $data = DotNotation::set($button, $request);

        return !is_null($data) ? $function($request) : false;
    }

    /**
     * Get address IP current user.
     *
     * @return array|false|string
     */
    public function ip()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = 'UNKNOWN';
        }

        return compare($ip, '::1') ? '127.0.0.1' : $ip;
    }

    /**
     * Get lang browser current user.
     *
     * @param bool $full
     * @return string
     */
    public function locale(bool $full = false): string
    {
        $server = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null;

        if (!empty($server)) {
            $server = substr($server, 0, strpos($server, ','));

            // Extracts the language short code.
            return !$full ? substr($server, 0, 2) : $server;
        }

        return Config::get('app.locale.default');
    }

    /**
     * Clears values from the global $_GET array
     *
     * @param string $key
     * @param bool $int
     * @return array|int|string|null
     */
    private function fromGET(string $key, bool $int = true)
    {
        if (isset($_GET[$key])) {
            $GET = Filter::clear($_GET[$key], false);

            return $int ? (int)$GET : $GET;
        }

        return null;
    }

    /**
     * Clears values from the global $_POST array
     *
     * @param string $key
     * @param bool $trim
     * @return array|string|null
     */
    private function fromPOST(string $key, bool $trim = true)
    {
        if (isset($_POST[$key])) {
            $POST = $_POST[$key];
            $retPOST = [];

            if (is_string($POST)) {
                return Filter::clear($_POST[$key], $trim);
            }

            foreach ($POST as $fields => $values) {
                if (is_string($values)) {
                    $retPOST[$fields] = Filter::clear($values, $trim);
                } else {
                    foreach ($values as $value) {
                        $field = $POST[$value];
                        $retPOST[$field['name']] = Filter::clear($field['value'], $trim);
                    }
                }
            }

            return $retPOST;
        }

        return null;
    }
}
