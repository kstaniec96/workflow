<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\User\Posts;

interface PostCommentsInterface
{
    public function create(int $id, array $validated): void;

    public static function qntyComments(int $postId): int;

    public static function owner(int $id): bool;
}
