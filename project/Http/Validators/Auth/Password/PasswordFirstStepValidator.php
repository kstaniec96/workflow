<?php

declare(strict_types=1);

namespace Project\Http\Validators\Auth\Password;

use Simpler\Components\Http\Validator\FormValidator;

class PasswordFirstStepValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function fields(): array
    {
        return [];
    }

    public function errors(): array
    {
        return [];
    }
}
