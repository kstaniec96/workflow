<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\User\Posts;

interface PostLikesInterface
{
    public function up(int $postId): void;

    public function down(int $postId): void;

    public static function qntyLikes(int $postId): int;

    public static function owner(int $id): bool;
}
