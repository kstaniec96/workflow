<?php

declare(strict_types=1);

namespace Project\Http\Controllers\Auth;

use Project\Http\Validators\Auth\Register\RegisterFirstStepValidator;
use Project\Http\Validators\Auth\Register\RegisterSecondStepValidator;
use Project\Models\Group;
use Simpler\Components\Enums\State;
use Simpler\Components\Http\BaseController;
use Project\Services\Auth\RegisterService;

class RegisterController extends BaseController
{
    public function index(): string
    {
        return $this->render('auth/register/first');
    }

    public function verify(string $token, RegisterService $registerService): string
    {
        if (!$registerService->verify($token)) {
            redirect()->to(route('home.index'));
        }

        $groups = Group::query()->where('active', State::ACTIVE)->get();

        return $this->render('auth/register/second', compact('groups', 'token'));
    }

    public function init(RegisterFirstStepValidator $validator, RegisterService $registerService): string
    {
        $registerService->init($validator->validated());

        return $this->json();
    }

    public function groups(RegisterSecondStepValidator $validator, RegisterService $registerService): string
    {
        $registerService->groups($validator->validated());

        return $this->json();
    }
}
