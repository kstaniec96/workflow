<?php
/**
 * This class for Controllers.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components\Http;

use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Http\Routers\View;

abstract class BaseController implements BaseControllerInterface
{
    /**
     * Render view template.
     *
     * @param string $view
     * @param array $params
     * @return void
     */
    public function render(string $view, array $params = []): string
    {
        return View::render($view, $params);
    }

    /**
     * Render block template.
     *
     * @param string $view
     * @param string $blockName
     * @param array $params
     * @return void
     */
    public function renderBlock(string $view, string $blockName, array $params = []): string
    {
        return View::renderBlock($view, $blockName, $params);
    }

    /**
     * Share view params.
     *
     * @param string $key
     * @param mixed $value
     * @return View
     */
    public function share(string $key, $value): View
    {
        return View::share($key, $value);
    }

    /**
     * Success json response
     *
     * @param mixed $data
     * @param int $status
     * @return string
     */
    public function json($data = null, int $status = HttpStatus::OK): string
    {
        return response()->json($data, $status);
    }

    /**
     * Error json response
     *
     * @param mixed $error
     * @return string
     */
    public function error($error = null): string
    {
        return response()->error($error);
    }
}
