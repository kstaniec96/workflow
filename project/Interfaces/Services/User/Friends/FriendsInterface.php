<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\User\Friends;

interface FriendsInterface
{
    public function add(int $id): void;

    public function remove(int $id): void;

    public static function isFriends(int $friendId): bool;
}
