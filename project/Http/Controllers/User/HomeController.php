<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Models\Post;
use Simpler\Components\Enums\State;

class HomeController extends UserController
{
    public function index(): string
    {
        $posts = Post::query()
            ->where('disabled', State::NO)
            ->orderBy('created_at', 'DESC')
            ->get('*');

        return $this->render('user/index', compact('posts'));
    }
}
