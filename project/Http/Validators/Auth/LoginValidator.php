<?php

declare(strict_types=1);

namespace Project\Http\Validators\Auth;

use Simpler\Components\Http\Validator\FormValidator;

class LoginValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function fields(): array
    {
        return [
            'password' => 'hasło',
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
