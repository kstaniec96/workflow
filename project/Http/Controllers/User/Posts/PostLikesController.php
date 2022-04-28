<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User\Posts;

use Project\Services\User\Posts\PostLikesService;
use Simpler\Components\Http\BaseController;

class PostLikesController extends BaseController
{
    public function up(int $id, PostLikesService $postLikes): string
    {
        $postLikes->up($id);

        return $this->json();
    }

    public function down(int $id, PostLikesService $postLikes): string
    {
        $postLikes->down($id);

        return $this->json();
    }
}
