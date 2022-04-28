<?php

declare(strict_types=1);

namespace Project\Models;

use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $message
 */
class PostComment extends Model
{
    protected $relations = [
        'user' => [User::class, 'user_id', 'user_id'],
        'post' => [Post::class, 'post_id', 'post_id'],
    ];
}
