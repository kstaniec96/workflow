<?php

declare(strict_types=1);

namespace Project\Http\Validators\Auth\Password;

use Simpler\Components\Http\Validator\FormValidator;

class PasswordSecondStepValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'password' => 'required|password',
            'password_confirm' => 'required|password',
            'token' => 'required|string',
        ];
    }

    public function fields(): array
    {
        return [
            'password' => 'hasło',
            'password_confirm' => 'potwierdź hasło'
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
