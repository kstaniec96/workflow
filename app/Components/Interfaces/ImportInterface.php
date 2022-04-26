<?php

namespace Simpler\Components\Interfaces;

interface ImportInterface
{
    /**
     * @param $path
     * @param bool $return
     * @param string|null $omission
     * @return mixed
     */
    public static function import($path, bool $return = false, string $omission = null);
}
