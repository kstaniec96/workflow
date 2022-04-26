<?php

namespace Simpler\Components\Requests\Interfaces;

interface RequestInterface
{
    /**
     * @param bool $only_data_sent
     * @return bool
     */
    public function isPost(bool $only_data_sent = false): bool;

    /**
     * @param bool $only_data_sent
     * @return bool
     */
    public function isGet(bool $only_data_sent = false): bool;

    /**
     * @param bool $only_data_sent
     * @return bool
     */
    public function isPut(bool $only_data_sent = false): bool;

    /**
     * @return bool
     */
    public function isApi(): bool;

    /**
     * @return string
     */
    public function isMethod(): string;

    /**
     * @param string $button
     * @param $function
     * @return mixed
     */
    public function submit(string $button, $function);

    /**
     * @return array|false|string
     */
    public function ip();

    /**
     * @param bool $full
     * @return string
     */
    public function locale(bool $full = false): string;

    /**
     * @param string $key
     * @param bool $option
     * @return mixed
     */
    public function get(string $key, bool $option = false);

    /**
     * @return array
     */
    public function all(): array;
}
