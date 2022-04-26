<?php

namespace Simpler\Components\Response\Interfaces;

use Simpler\Components\Enums\HttpStatus;

interface ResponseInterface
{
    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @param bool $json
     * @return string
     */
    public function json(
        $data = null,
        int $status = HttpStatus::OK,
        array $headers = [],
        int $options = 0,
        bool $json = false
    ): string;

    /**
     * @param $error
     * @return string
     */
    public function error($error): string;

    /**
     * @return array
     */
    public function headers(): array;

    /**
     * @param int|null $status
     * @return bool|int
     */
    public function status(?int $status = null);

    /**
     * @param int $status
     * @return bool
     */
    public function hasStatus(int $status = HttpStatus::OK): bool;

    /**
     * @param int $status
     * @param null|string $contentType
     * @return void
     */
    public function setStatusHeader(int $status, string $contentType = 'Content-Type: text/html'): void;

    /**
     * @param string $name
     * @param $value
     * @return void
     */
    public function setHeader(string $name, $value): void;
}
