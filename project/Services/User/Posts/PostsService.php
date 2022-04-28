<?php

declare(strict_types=1);

namespace Project\Services\User\Posts;

use Exception;
use Project\Interfaces\Services\User\Posts\PostsInterface;
use Project\Models\Post;
use Project\Models\PostComment;
use Project\Models\User;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Enums\State;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;

class PostsService implements PostsInterface
{
    public function create(array $validated): void
    {
        try {
            Post::query()->insert([
                'user_id' => Auth::id(),
                'content' => $validated['message'],
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function edit(int $id, array $validated): void
    {
        $message = $validated['message'];

        try {
            $post = Post::query()->where('id', $id);

            if (compare($post->value('content'), $message)) {
                throw new UnprocessableException(__('app.You want to update unchanged data'));
            }

            $post->update([
                'content' => $message,
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function delete(int $id): void
    {
        try {
            Post::query()->where('id', $id)->update(['disabled' => State::YES]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public static function owner(int $postId): bool
    {
        return Post::query()
            ->where('user_id', Auth::id())
            ->where('id', $postId)
            ->exists();
    }

    public static function user(int $userId)
    {
        return User::query()->where('id', $userId)->first();
    }

    public static function comments(int $postId): array
    {
        try {
            return PostComment::query()
                ->where('post_id', $postId)
                ->get();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }
}
