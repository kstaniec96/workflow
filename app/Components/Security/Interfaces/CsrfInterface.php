<?php

namespace Simpler\Components\Security\Interfaces;

interface CsrfInterface
{
    /**
     * @return void
     */
    public static function init(): void;

    /**
     * @return void
     */
    public static function check(): void;

    /**
     * @return string
     */
    public static function getToken(): string;
}
