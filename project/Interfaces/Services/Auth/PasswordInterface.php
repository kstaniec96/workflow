<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\Auth;

interface PasswordInterface
{
    public function init(array $validated): void;

    public function change(array $validated): void;
}
