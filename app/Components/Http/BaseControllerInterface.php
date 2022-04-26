<?php

namespace Simpler\Components\Http;

use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Http\Routers\View;

interface BaseControllerInterface
{
    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string;

    /**
     * @param string $view
     * @param string $blockName
     * @param array $params
     * @return string
     */
    public function renderBlock(string $view, string $blockName, array $params = []): string;

    /**
     * @param string $key
     * @param $value
     * @return View
     */
    public function share(string $key, $value): View;

    /**
     * @param mixed $data
     * @param int $status
     * @return string
     */
    public function json($data = null, int $status = HttpStatus::OK): string;

    /**
     * @param mixed $error
     * @return string
     */
    public function error($error = null): string;
}
