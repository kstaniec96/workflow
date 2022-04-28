<?php

declare(strict_types=1);

namespace Project\Services\User\DefaultGroups;

use Exception;
use Project\Interfaces\Services\User\DefaultGroups\DefaultGroupsInterface;
use Project\Models\User\UserMainGroup;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Exceptions\ThrowException;

class DefaultGroupsService implements DefaultGroupsInterface
{
    public function add(int $id): void
    {
        try {
            UserMainGroup::query()->insert([
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
            UserMainGroup::query()
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

        return UserMainGroup::query()
            ->where('user_id', $id)
            ->where('group_id', $groupId)
            ->exists();
    }
}
