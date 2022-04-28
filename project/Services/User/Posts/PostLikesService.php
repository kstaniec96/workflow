<?php

declare(strict_types=1);

namespace Project\Services\User\Posts;

use Exception;
use Project\Interfaces\Services\User\Posts\PostLikesInterface;
use Project\Models\PostLike;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Exceptions\ThrowException;

class PostLikesService implements PostLikesInterface
{
    public function up(int $postId): void
    {
        try {
            PostLike::query()
                ->insert([
                    'post_id' => $postId,
                    'user_id' => Auth::id(),
                ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function down(int $postId): void
    {
        try {
            PostLike::query()
                ->where('post_id', $postId)
                ->where('user_id', Auth::id())
                ->delete();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public static function qntyLikes(int $postId): int
    {
        try {
            return PostLike::query()
                ->where('post_id', $postId)
                ->count();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public static function owner(int $id): bool
    {
        return PostLike::query()
            ->where('post_id', $id)
            ->where('user_id', Auth::id())
            ->exists();
    }
}
