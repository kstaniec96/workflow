<?php

declare(strict_types=1);

namespace Project\Http\Controllers;

use Project\Models\Group;
use Project\Models\User;
use Project\Models\User\UserCreatedGroup;
use Simpler\Components\Enums\State;
use Simpler\Components\Http\BaseController;

class HomeController extends BaseController
{
    public function index(): string
    {
        $groups = Group::query()->where('active', State::ACTIVE)->get();

        $users = User::query()
            ->where('active', State::ACTIVE)
            ->where('verified', State::YES)
            ->get();

        $usersGroups = UserCreatedGroup::query()
            ->with('user')
            ->get('ucg.*, uuid');

        return $this->render('home', compact('groups', 'users', 'usersGroups'));
    }
}
