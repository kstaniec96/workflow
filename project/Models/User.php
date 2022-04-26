<?php

declare(strict_types=1);

namespace Project\Models;

use Project\Models\User\UserCreatedGroup;
use Project\Models\User\UserFriend;
use Project\Models\User\UserGroup;
use Project\Models\User\UserMainGroup;
use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property string $uuid
 * @property string $email
 * @property string $password
 * @property string $username
 * @property bool $active
 * @property bool $verified
 * @property string $avatar
 * @property string $bg
 * @property string $expired
 * @property string $token
 */
class User extends Model
{
    protected $relations = [
        'posts' => [Post::class, 'id', 'user_id'],
        'likes' => [PostLike::class, 'id', 'user_id'],
        'userCreatedGroups' => [UserCreatedGroup::class, 'id', 'user_id'],
        'friends' => [UserFriend::class, 'id', 'user_id'],
        'userGroups' => [UserGroup::class, 'id', 'user_id'],
        'userMainGroups' => [UserMainGroup::class, 'id', 'user_id'],
    ];
}
