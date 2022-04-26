<?php

declare(strict_types=1);

namespace Project\Http\Controllers\Auth;

use Project\Http\Validators\Auth\Password\PasswordFirstStepValidator;
use Project\Http\Validators\Auth\Password\PasswordSecondStepValidator;
use Project\Services\Auth\PasswordService;
use Project\Services\Auth\RegisterService;
use Simpler\Components\Http\BaseController;

class PasswordController extends BaseController
{
    public function index(): string
    {
        return $this->render('auth/password/first');
    }

    public function verify(string $token, RegisterService $registerService): string
    {
        if (!$registerService->verify($token)) {
            redirect()->to(route('home.index'));
        }

        return $this->render('auth/password/second', compact('token'));
    }

    public function init(PasswordFirstStepValidator $validator, PasswordService $passwordService): string
    {
        $passwordService->init($validator->validated());

        return $this->json();
    }

    public function change(PasswordSecondStepValidator $validator, PasswordService $passwordService): string
    {
        $passwordService->change($validator->validated());

        return $this->json();
    }
}
