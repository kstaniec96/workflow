<?php

declare(strict_types=1);

namespace Project\Http\Validators\Auth\Register;

use Simpler\Components\Http\Validator\FormValidator;

class RegisterFirstStepValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|password',
        ];
    }

    public function fields(): array
    {
        return [
            'password' => 'hasło',
            'username' => 'nazwa użytkownika',
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
