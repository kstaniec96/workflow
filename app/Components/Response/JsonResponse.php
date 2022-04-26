<?php

namespace Simpler\Components\Response;

use Simpler\Components\Enums\HttpStatus;
use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    /**
     * Constructor.
     *
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param null|int $options
     * @param bool $json
     * @return void
     */
    public function __construct(
        $data = null,
        int $status = HttpStatus::OK,
        array $headers = [],
        ?int $options = null,
        bool $json = false
    ) {
        $this->encodingOptions = $options ?? JSON_THROW_ON_ERROR;

        parent::__construct($data, $status, $headers, $json);
    }
}
