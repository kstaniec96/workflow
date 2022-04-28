<?php

declare(strict_types=1);

namespace Project\Services\User\Groups;

use Exception;
use Project\Interfaces\Services\User\Groups\GroupsInterface;
use Project\Models\User\UserGroup;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Exceptions\ThrowException;

class GroupsService implements GroupsInterface
{
    public function add(int $id): void
    {
        try {
            UserGroup::query()->insert([
                'user_id' => Auth::id(),
                'group_id' => $id,
            ]);

        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function remove(int $id): void
    {
        try {
            UserGroup::query()
                ->where('user_id', Auth::id())
                ->where('group_id', $id)
                ->delete();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public static function joinedGroup(int $groupId): bool
    {
        $id = Auth::id();

        return UserGroup::query()
            ->where('user_id', $id)
            ->where('group_id', $groupId)
            ->exists();
    }
}
