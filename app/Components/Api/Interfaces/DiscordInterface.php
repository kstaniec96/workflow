<?php

namespace Simpler\Components\Api\Interfaces;

interface DiscordInterface
{
    /**
     * @param string $message
     * @param $trace
     * @return mixed
     */
    public static function send(string $message, $trace = null);
}
