<?php

declare(strict_types=1);

namespace Project\Models;

use Project\Models\User\UserGroup;
use Simpler\Components\Database\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property bool $active
 */
class Group extends Model
{
    protected $relations = [
        'users' => [UserGroup::class, 'id', 'group_id'],
    ];
}
