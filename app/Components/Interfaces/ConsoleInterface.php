<?php

namespace Simpler\Components\Interfaces;

interface ConsoleInterface
{
    /**
     * @param array|null $argv
     * @return void
     */
    public static function init(?array $argv): void;

    /**
     * @param string|null $option
     * @return mixed
     */
    public function getOptions(?string $option = null);

    /**
     * @param string|null $argv
     * @return mixed
     */
    public function getArgv(?string $argv = null);

    /**
     * @param string $str
     * @return void
     */
    public function info(string $str): void;

    /**
     * @param string $str
     * @return void
     */
    public function success(string $str): void;

    /**
     * @param string $str
     * @return void
     */
    public function warning(string $str): void;

    /**
     * @param string $str
     * @return void
     */
    public function error(string $str): void;
}
