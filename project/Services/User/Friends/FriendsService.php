<?php

declare(strict_types=1);

namespace Project\Services\User\Friends;

use Exception;
use Project\Interfaces\Services\User\Friends\FriendsInterface;
use Project\Models\User\UserFriend;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Database\DB;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;

class FriendsService implements FriendsInterface
{
    public function add(int $id): void
    {
        DB::beginTransaction();

        try {
            $maxFriends = env('MAX_NUMBER_OF_FRIENDS');

            if ((Auth::user('friends')->count() + 1) > $maxFriends) {
                throw new UnprocessableException(
                    __('app.You can invite up to friends', [
                        'friends' => $maxFriends,
                    ])
                );
            }

            UserFriend::query()->insert([
                'user_id' => Auth::id(),
                'friend_id' => $id,
            ]);

            UserFriend::query()->insert([
                'friend_id' => Auth::id(),
                'user_id' => $id,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new ThrowException($e);
        }
    }

    public function remove(int $id): void
    {
        DB::beginTransaction();

        try {
            UserFriend::query()
                ->where('user_id', Auth::id())
                ->where('friend_id', $id)
                ->delete();

            UserFriend::query()
                ->where('friend_id', Auth::id())
                ->where('user_id', $id)
                ->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw new ThrowException($e);
        }
    }

    public static function isFriends(int $friendId): bool
    {
        $id = Auth::id();

        return UserFriend::query()
            ->where('user_id', $id)
            ->where('friend_id', $friendId)
            ->exists();
    }
}
