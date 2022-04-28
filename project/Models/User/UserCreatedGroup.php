<?php

declare(strict_types=1);

namespace Project\Models\User;

use Project\Models\User;
use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property string $bg
 * @property string $disabled
 */
class UserCreatedGroup extends Model
{
    protected $alias = 'ucg';

    protected $relations = [
        'user' => [User::class, 'user_id', 'id'],
    ];
}
