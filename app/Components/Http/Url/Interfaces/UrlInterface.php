<?php

namespace Simpler\Components\Http\Url\Interfaces;

use Simpler\Components\Http\Url\Query;

interface UrlInterface
{
    /**
     * @param bool $strip
     * @return string
     */
    public function current(bool $strip = true): string;

    /**
     * @param string $path
     * @return string
     */
    public function join(string $path): string;

    /**
     * @return string
     */
    public function pathname(): string;

    /**
     * @return string
     */
    public function host(): string;

    /**
     * @return string
     */
    public function protocol(): string;

    /**
     * @return string|null
     */
    public function hash(): ?string;

    /**
     * @return string
     */
    public function domain(): string;

    /**
     * @return Query
     */
    public function query(): Query;
}
