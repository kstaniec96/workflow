<?php

declare(strict_types=1);

namespace Project\Models\User;

use Project\Models\User;
use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 */
class UserGroup extends Model
{
    protected $alias = 'ug';

    protected $relations = [
        'user' => [User::class, 'user_id', 'id'],
        'group' => [UserCreatedGroup::class, 'group_id', 'id'],
    ];
}
