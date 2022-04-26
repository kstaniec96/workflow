<?php
/**
 * This class manage redirect to route.
 *
 * @package Simpler
 * @subpackage HTTP
 * @version 2.0
 */

namespace Simpler\Components\Requests;

use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Requests\Interfaces\RedirectInterface;

class Redirect implements RedirectInterface
{
    /**
     * Redirect to specific page.
     *
     * @param string $to
     * @param int $code
     * @return void
     */
    public function to(string $to = '/', int $code = HttpStatus::FOUND): void
    {
        ob_start();

        response()->status($code);
        header('Location:'.$to);

        exit;
    }

    /**
     * Set flash session with redirect.
     *
     * @param string $status
     * @param string $message
     * @return $this
     */
    public function with(string $status, string $message): Redirect
    {
        session()->flash($status, $message);

        return $this;
    }
}
