<?php

declare(strict_types=1);

namespace Project\Repositories;

use Project\Interfaces\Repositories\UserRepositoryInterface;
use Project\Models\User;
use Simpler\Components\Auth\Auth;

class UserRepository implements UserRepositoryInterface
{
    public static function findById(int $id = null): ?object
    {
        return User::query()->where('id', $id ?? Auth::id())->first();
    }
}
