<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Models\User\UserCreatedGroup;
use Project\Models\User\UserFriend;
use Project\Models\User\UserGroup;
use Project\Models\User\UserMainGroup;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Http\BaseController;

abstract class UserController extends BaseController
{
    public function __construct()
    {
        $userMainGroups = UserMainGroup::query()
            ->where('user_id', Auth::id())
            ->with('group g')
            ->get('g.*');

        $usersGroup = UserGroup::query()
            ->where('ug.user_id', Auth::id())
            ->with(['group g', 'user u'])
            ->get('g.*, u.*');

        $userGroups = Auth::user('userCreatedGroups ucg')->get('ucg.*, uuid') ?? [];

        $userFriends = UserFriend::query()
            ->where('user_id', Auth::id())
            ->with('friend f')
            ->get('f.*');

        $this
            ->share('userMainGroups', $userMainGroups)
            ->share('usersGroup', $usersGroup)
            ->share('userFriends', $userFriends)
            ->share('userGroups', $userGroups);
    }
}
