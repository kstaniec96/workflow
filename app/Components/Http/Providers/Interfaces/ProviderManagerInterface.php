<?php

namespace Simpler\Components\Http\Providers\Interfaces;

interface ProviderManagerInterface
{
    /**
     * @param string $item
     * @param null|string $pattern
     * @return mixed
     */
    public function has(string $item, ?string $pattern = null): bool;

    /**
     * @param string|array $item
     * @param mixed $value
     * @param string|null $expire
     * @return bool
     */
    public function create($item, $value = null, ?string $expire = null): bool;

    /**
     * @param array|string $item
     * @param mixed $value
     * @return bool
     */
    public function flash($item, $value = null): bool;

    /**
     * @param string $item
     * @param $value
     * @param string|null $expire
     * @return bool
     */
    public function refresh(string $item, $value = null, ?string $expire = null): bool;

    /**
     * @param string|null $item
     * @return array
     */
    public function all(?string $item = null): array;

    /**
     * @param string $item
     * @param string|null $pattern
     * @return array|string|null
     */
    public function get(string $item, ?string $pattern = null);

    /**
     * @param string $item
     * @param bool $timestamp
     * @return mixed
     */
    public function expire(string $item, bool $timestamp = true);

    /**
     * @param string $item
     * @return bool
     */
    public function delete(string $item): bool;
}
