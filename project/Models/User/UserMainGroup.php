<?php

declare(strict_types=1);

namespace Project\Models\User;

use Project\Models\Group;
use Project\Models\User;
use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 */
class UserMainGroup extends Model
{
    protected $relations = [
        'user' => [User::class, 'user_id', 'id'],
        'group' => [Group::class, 'group_id', 'id'],
    ];
}
