<?php

namespace Project\Interfaces\Services\Auth;

interface LoginInterface
{
    public function api(array $validated): array;
}
