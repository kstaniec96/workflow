<?php
/**
 * This class gets the query from
 * the URL and modifies it accordingly.
 *
 * @package Simpler
 * @subpackage Requests
 * @version 2.0
 */

namespace Simpler\Components\Http\Url;

use Simpler\Components\Http\Url\Interfaces\QueryInterface;
use Simpler\Components\Http\Validator\Validator;
use Simpler\Components\Security\Filter;

class Query extends Url implements QueryInterface
{
    /** @var array */
    private array $queries = [];

    /**
     * Retrieves the URL with the query and
     * converts it to an associative array.
     */
    public function __construct()
    {
        $url = parse_url($this->current(), PHP_URL_QUERY);
        $parts = parse_url('?'.$url); // Simple trick to make it key value

        if (isset($parts['query'])) {
            parse_str(html_entity_decode($parts['query']), $query);
            $this->queries = $query;
        }
    }

    /**
     * Get all query params.
     *
     * @return array
     */
    public function all(): array
    {
        $data = [];

        foreach ($this->queries as $name => $value) {
            $data[$name] = $this->value($name);
        }

        return $data;
    }

    /**
     * Gets data about a particular
     * query given as a parameter.
     *
     * @param string $name
     * @param string|null $pattern
     * @return null|string
     */
    public function get(string $name, ?string $pattern = null): ?string
    {
        if ($this->has($name)) {
            return $this->value($name, $pattern);
        }

        return null;
    }

    /**
     * Check the query params is exists.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->queries[$name] ?? false;
    }

    /**
     * Generate URL-encoded query string.
     *
     * @param array $queryData
     * @param string|null $url
     * @param string $numericPrefix
     * @param string $separator
     * @param int $encType
     * @return string
     */
    public function build(
        array $queryData,
        ?string $url = null,
        string $numericPrefix = '',
        string $separator = '&',
        int $encType = PHP_QUERY_RFC1738
    ): string {
        $url = $url ? trim($url, '/') : $this->current();

        if (empty($queryData)) {
            return $url;
        }

        $queries = [];

        foreach ($this->all() as $key => $query) {
            $queries[$key] = $query['value'];
        }

        return $url.'/?'.http_build_query(
                array_merge($queries, $queryData),
                $numericPrefix,
                $separator,
                $encType
            );
    }

    /**
     * Filter and validation query value.
     *
     * @param string $name
     * @param string|null $pattern
     * @return string|null
     */
    private function value(string $name, ?string $pattern = null): ?string
    {
        $value = Filter::clear($this->queries[$name] ?? '', true);

        if (!is_null($pattern) && !Validator::validation($value, $pattern)) {
            return null;
        }

        return $value;
    }
}
