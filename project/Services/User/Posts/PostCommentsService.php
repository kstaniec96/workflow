<?php

declare(strict_types=1);

namespace Project\Services\User\Posts;
use Exception;
use Project\Interfaces\Services\User\Posts\PostCommentsInterface;
use Project\Models\PostComment;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;

class PostCommentsService implements PostCommentsInterface
{
    public function create(int $id, array $validated): void
    {
        try {
            PostComment::query()->insert([
                'user_id' => Auth::id(),
                'post_id' => $id,
                'message' => $validated['message'],
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function edit(int $id, array $validated): void
    {
        $message = $validated['message'];

        try {
            $post = PostComment::query()->where('id', $id);

            if (compare($post->value('message'), $message)) {
                throw new UnprocessableException(__('app.You want to update unchanged data'));
            }

            $post->update([
                'message' => $message,
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function delete(int $id): void
    {
        try {
            PostComment::query()
                ->where('user_id', Auth::id())
                ->where('id', $id)
                ->delete();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public static function qntyComments(int $postId): int
    {
        try {
            return PostComment::query()
                ->where('post_id', $postId)
                ->count();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public static function owner(int $id): bool
    {
        return PostComment::query()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->exists();
    }
}
