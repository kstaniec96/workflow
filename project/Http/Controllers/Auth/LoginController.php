<?php

declare(strict_types=1);

namespace Project\Http\Controllers\Auth;

use Simpler\Components\Http\BaseController;
use Exception;
use Project\Http\Validators\Auth\LoginValidator;
use Project\Services\Auth\LoginService;
use RuntimeException;

class LoginController extends BaseController
{
    public function index(): string
    {
        return $this->render('auth/login');
    }

    public function api(LoginValidator $validator, LoginService $loginService): string
    {
        return $this->json($loginService->api($validator->validated()));
    }
}
