<?php

declare(strict_types=1);

namespace Project\Http\Controllers\Auth;

use Simpler\Components\Auth\Auth;
use Simpler\Components\Http\BaseController;

class LogoutController extends BaseController
{
    public function logout(): void
    {
        if (Auth::logout()) {
            redirect()->to(route('auth.login.index'));
        }
    }
}
