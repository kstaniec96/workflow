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
            'password' => __('validator.fields.password'),
            'password_confirm' => __('validator.fields.password-confirm'),
        ];
    }

    public function errors(): array
    {
        return [
            'password' => [
                'password' => __('validator.rules.password'),
            ],

            'password_confirm' => [
                'password' => __('validator.rules.password'),
            ],
        ];
    }
}
