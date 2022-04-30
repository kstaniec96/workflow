<?php

namespace Project\Interfaces\Services\Auth;

interface RegisterInterface
{
    public function init(array $validated): void;

    public function verify(?string $token, bool $reset = false): bool;

    public function groups(array $validated): void;
}
