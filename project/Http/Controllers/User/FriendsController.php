<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

class FriendsController extends UserController
{
    public function index(): string
    {
        return $this->render('user/friends');
    }
}
