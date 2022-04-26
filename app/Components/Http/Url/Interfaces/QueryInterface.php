<?php

namespace Simpler\Components\Http\Url\Interfaces;

interface QueryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param string $name
     * @param string|null $pattern
     * @return object
     */
    public function get(string $name, ?string $pattern = null): ?string;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param array $queryData
     * @param string|null $url
     * @param string $numericPrefix
     * @param string $separator
     * @param int $encType
     * @return string
     */
    public function build(
        array $queryData,
        ?string $url,
        string $numericPrefix = '',
        string $separator = '&',
        int $encType = PHP_QUERY_RFC1738
    ): string;
}
