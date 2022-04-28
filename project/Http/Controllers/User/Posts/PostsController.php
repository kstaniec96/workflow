<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User\Posts;

use Project\Http\Validators\User\Posts\CreateOrEditPostValidator;
use Project\Services\User\Posts\PostsService;
use Simpler\Components\Http\BaseController;

class PostsController extends BaseController
{
    public function create(CreateOrEditPostValidator $validator, PostsService $postsService): string
    {
        $postsService->create($validator->validated());

        return $this->json();
    }

    public function edit(int $id, CreateOrEditPostValidator $validator, PostsService $postsService): string
    {
        $postsService->edit($id, $validator->validated());

        return $this->json();
    }

    public function delete(int $id, PostsService $postsService): string
    {
        $postsService->delete($id);

        return $this->json();
    }
}
