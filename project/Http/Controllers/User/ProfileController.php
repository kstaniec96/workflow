<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Models\Post;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Enums\State;

class ProfileController extends UserController
{
    public function index(): string
    {
        $posts = Post::query()
            ->where('disabled', State::NO)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->get('*');

        return $this->render('user/profile', compact('posts'));
    }
}
