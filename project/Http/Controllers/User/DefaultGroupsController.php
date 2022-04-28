<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Models\Group;
use Project\Services\User\DefaultGroups\DefaultGroupsService;

class DefaultGroupsController extends UserController
{
    public function index(): string
    {
        $groups = Group::query()->get();

        return $this->render('user/default-groups', compact('groups'));
    }

    public function add(int $id, DefaultGroupsService $groupsService): string
    {
        $groupsService->add($id);

        return $this->json();
    }

    public function remove(int $id, DefaultGroupsService $groupsService): string
    {
        $groupsService->remove($id);

        return $this->json();
    }
}
