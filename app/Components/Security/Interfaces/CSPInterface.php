<?php

namespace Simpler\Components\Security\Interfaces;

interface CSPInterface
{
    /**
     * @return void
     */
    public static function init(): void;

    /**
     * @return string
     */
    public static function nonce(): string;
}
