<?php

declare(strict_types=1);

namespace Project\Services\User\Groups;

use Exception;
use Project\Interfaces\Services\User\Groups\GroupsInterface;
use Project\Models\User\UserCreatedGroup;
use Project\Models\User\UserGroup;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;

class GroupsService implements GroupsInterface
{
    public function add(int $id): void
    {
        try {
            $maxGroupsJoined = env('MAX_NUMBER_OF_GROUPS_JOINED');

            if ((Auth::user('userGroups')->count() + 1) > $maxGroupsJoined) {
                throw new UnprocessableException(
                    __('app.You can join a maximum groups', [
                        'groups' => $maxGroupsJoined,
                    ])
                );
            }

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
