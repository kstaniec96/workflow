<?php

namespace Simpler\Components\Api\Interfaces;

interface SlackInterface
{
    /**
     * @param array $params
     * @param string|null $template
     * @return void
     */
    public static function send(array $params, ?string $template = null): void;
}
