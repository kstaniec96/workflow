<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Models\User;
use Project\Services\User\Friends\FriendsService;
use Simpler\Components\Enums\State;

class FriendsController extends UserController
{
    public function index(): string
    {
        $users = User::query()
            ->where('active', State::ACTIVE)
            ->where('verified', State::YES)
            ->get();

        return $this->render('user/friends', compact('users'));
    }

    public function add(int $id, FriendsService $friendsService): string
    {
        $friendsService->add($id);

        return $this->json();
    }

    public function remove(int $id, FriendsService $friendsService): string
    {
        $friendsService->remove($id);

        return $this->json();
    }
}
