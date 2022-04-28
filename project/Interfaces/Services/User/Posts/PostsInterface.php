<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\User\Posts;

interface PostsInterface
{
    public function create(array $validated): void;

    public function edit(int $id, array $validated): void;

    public function delete(int $id): void;

    public static function owner(int $postId): bool;

    public static function user(int $userId);

    public static function comments(int $postId): array;
}
