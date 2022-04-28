<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User\Posts;

use Project\Http\Validators\User\Posts\CreateOrEditPostCommentsValidator;
use Project\Services\User\Posts\PostCommentsService;
use Simpler\Components\Http\BaseController;

class PostCommentsController extends BaseController
{
    public function create(
        int $id,
        CreateOrEditPostCommentsValidator $validator,
        PostCommentsService $postCommentsService
    ): string {
        $postCommentsService->create($id, $validator->validated());

        return $this->json();
    }

    public function edit(
        int $id,
        CreateOrEditPostCommentsValidator $validator,
        PostCommentsService $postCommentsService
    ): string {
        $postCommentsService->edit($id, $validator->validated());

        return $this->json();
    }

    public function delete(int $id, PostCommentsService $postCommentsService): string
    {
        $postCommentsService->delete($id);

        return $this->json();
    }
}
