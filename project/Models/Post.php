<?php

declare(strict_types=1);

namespace Project\Models;

use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property string $disabled
 */
class Post extends Model
{
    protected $relations = [
        'user' => [User::class, 'user_id', 'id'],
        'likes' => [PostLike::class, 'id', 'post_id'],
        'comments' => [PostComment::class, 'id', 'post_id'],
    ];
}
