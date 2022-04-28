<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User\OwnGroups;

use Project\Http\Validators\User\OwnGroups\CreateOrEditOwnGroupsValidator;
use Project\Services\User\OwnGroups\OwnGroupsService;
use Simpler\Components\Http\BaseController;

class OwnGroupsController extends BaseController
{
    public function create(CreateOrEditOwnGroupsValidator $validator, OwnGroupsService $postsService): string
    {
        $postsService->create($validator->validated());

        return $this->json();
    }

    public function edit(int $id, CreateOrEditOwnGroupsValidator $validator, OwnGroupsService $postsService): string
    {
        $postsService->edit($id, $validator->validated());

        return $this->json();
    }

    public function delete(int $id, OwnGroupsService $postsService): string
    {
        $postsService->delete($id);

        return $this->json();
    }
}
