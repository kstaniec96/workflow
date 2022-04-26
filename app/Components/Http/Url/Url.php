<?php
/**
 * This class displays basic data from the URL.
 *
 * @package Simpler
 * @subpackage Request
 * @version 2.0
 */

namespace Simpler\Components\Http\Url;

use Simpler\Components\Http\Url\Interfaces\UrlInterface;
use Simpler\Components\Security\Filter;

class Url implements UrlInterface
{
    /**
     * Retrieves the entire current URL.
     *
     * @param bool $strip
     * @return string
     */
    public function current(bool $strip = true): string
    {
        static $filter, $scheme, $host;

        if (!$filter) {
            $filter = Filter::url($_SERVER['REQUEST_URI'], $strip);
            $host = $this->host();
            $scheme = $this->protocol();
        }

        return sprintf('%s://%s%s', $scheme, $host, $filter);
    }

    /**
     * Attaches a subpage to the URL.
     *
     * @param string $path
     * @return string
     */
    public function join(string $path): string
    {
        return $this->domain().'/'.$path;
    }

    /**
     * Retrieves only the URI part.
     *
     * @return string
     */
    public function pathname(): string
    {
        $pathname = str_replace($this->domain(), '', $this->current());

        if (compare($_SERVER['SERVER_SOFTWARE'], 'Apache')) {
            $pathname = str_replace(substr(ROOT_PATH, 1), '', $pathname);
        }

        if (strpos($pathname, '?') !== false) {
            $pathname = substr($pathname, 0, strpos($pathname, '?'));
        }

        return trim($pathname, '/');
    }

    /**
     * Retrieves the current host
     * of the page.
     *
     * @return string
     */
    public function host(): string
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Retrieves the current protocol
     * from the URL.
     *
     * @return string
     */
    public function protocol(): string
    {
        return (!isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) === 'off') ? 'http' : 'https';
    }

    /**
     * Retrieves everything after the #
     * sign from the URL.
     *
     * @return string|null
     */
    public function hash(): ?string
    {
        return parse_url($this->current(), PHP_URL_FRAGMENT);
    }

    /**
     * Get domain (protocol and host).
     *
     * @return string
     */
    public function domain(): string
    {
        return $this->protocol().'://'.$this->host();
    }

    /**
     * Query class instance.
     *
     * @return Query
     */
    public function query(): Query
    {
        return new Query();
    }
}
