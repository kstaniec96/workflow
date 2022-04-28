<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Models\User\UserCreatedGroup;
use Project\Services\User\Groups\GroupsService;
use Simpler\Components\Enums\State;

class GroupsController extends UserController
{
    public function index(): string
    {
        $groups = UserCreatedGroup::query()
            ->where('disabled', State::ENABLED)
            ->get();

        return $this->render('user/groups', compact('groups'));
    }

    public function add(int $id, GroupsService $groupsService): string
    {
        $groupsService->add($id);

        return $this->json();
    }

    public function remove(int $id, GroupsService $groupsService): string
    {
        $groupsService->remove($id);

        return $this->json();
    }
}
