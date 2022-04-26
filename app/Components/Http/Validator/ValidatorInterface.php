<?php

namespace Simpler\Components\Http\Validator;

interface ValidatorInterface
{
    /**
     * @param null|array $rules
     * @return array
     */
    public function validated(?array $rules): array;

    /**
     * @param string|null $value
     * @param string|null $pattern
     * @return bool
     */
    public static function validation(?string $value, ?string $pattern): bool;
}
