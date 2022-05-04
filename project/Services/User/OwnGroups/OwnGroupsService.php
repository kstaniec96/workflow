<?php

declare(strict_types=1);

namespace Project\Services\User\OwnGroups;

use Exception;
use Project\Interfaces\Services\User\OwnGroups\OwnGroupsInterface;
use Project\Models\User\UserCreatedGroup;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Enums\State;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;

class OwnGroupsService implements OwnGroupsInterface
{
    public function create(array $validated): void
    {
        try {
            $name = $validated['name'];
            $maxGroupsCreated = env('MAX_NUMBER_OF_GROUPS_CREATED');

            if ((Auth::user('userCreatedGroups')->count() + 1) > $maxGroupsCreated) {
                throw new UnprocessableException(
                    __('app.You can create up to groups', [
                        'groups' => $maxGroupsCreated,
                    ])
                );
            }

            if (UserCreatedGroup::query()->where('name', $name)->exists()) {
                throw new UnprocessableException(__('app.The specified group name already exists'));
            }

            UserCreatedGroup::query()->insert([
                'user_id' => Auth::id(),
                'name' => $name,
                'description' => $validated['description'],
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function edit(int $id, array $validated): void
    {
        $name = $validated['name'];
        $description = $validated['description'];

        try {
            $post = UserCreatedGroup::query()->where('id', $id);

            if (compare($post->value('content'), $name) && compare($post->value('description'), $description)) {
                throw new UnprocessableException(__('app.You want to update unchanged data'));
            }

            $post->update([
                'name' => $name,
                'description' => $description,
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function delete(int $id): void
    {
        try {
            UserCreatedGroup::query()->where('id', $id)->update(['disabled' => State::YES]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }
}
