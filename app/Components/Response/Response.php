<?php
/**
 * This class is used to handle response.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components\Response;

use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Response\Interfaces\ResponseInterface;
use Simpler\Components\Security\Filter;

class Response implements ResponseInterface
{
    /**
     * This method is used to send data via AJAX.
     *
     * @param mixed $data
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
        int $options = JSON_THROW_ON_ERROR,
        bool $json = false
    ): string {
        $status = $status < 100 ? HttpStatus::INTERNAL_SERVER_ERROR : $status;
        $this->status($status);

        if (is_string($data)) {
            $data = ['message' => $data];
        }

        return (new JsonResponse($data, $status, $headers, $options, $json))->getContent();
    }

    /**
     * JSON error data.
     *
     * @param $error
     * @return string
     */
    public function error($error): string
    {
        echo $this->json($error->getMessage(), $error->getCode());
        exit;
    }

    /**
     * This method retrieves all headers
     * sent by the server.
     *
     * @return array
     */
    public function headers(): array
    {
        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (compare(substr($name, 0, 5), 'HTTP_')) {
                $headers[str_replace(
                    ' ',
                    '-',
                    ucwords(
                        strtolower(
                            str_replace('_', ' ', substr($name, 5))
                        )
                    )
                )] = Filter::clear($value);
            }
        }

        return $headers;
    }

    /**
     * Get header value.
     *
     * @param string $header
     * @return string|null
     */
    public function getHeader(string $header): ?string
    {
        return $this->headers()[$header] ?? null;
    }

    /**
     * This method sets the headers
     *
     * @param string $name
     * @param mixed $value
     * @param int $status
     * @return void
     */
    public function setHeader(string $name, $value, int $status = HttpStatus::OK): void
    {
        header(Filter::clear($name).':'.Filter::clear($value), true, $status);
    }

    /**
     * This method sets the headers
     *
     * @param int $status
     * @param null|string $contentType
     * @return void
     */
    public function setStatusHeader(int $status, string $contentType = 'Content-Type: text/html'): void
    {
        http_response_code($status);
        $this->setHeader('Content-Type', $contentType);
    }

    /**
     * This method check the header status code.
     *
     * @param int $status
     * @return bool
     */
    public function hasStatus(int $status = HttpStatus::OK): bool
    {
        return compare($this->status(), $status);
    }

    /**
     * Get current http response code.
     *
     * @return bool|int
     */
    public function status(?int $status = null)
    {
        return $status ? http_response_code($status) : http_response_code();
    }
}
