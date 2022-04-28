<?php

declare(strict_types=1);

namespace Project\Http\Validators\User\Settings;

use Simpler\Components\Http\Validator\FormValidator;

class ChangePasswordSettingsValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|password',
        ];
    }

    public function fields(): array
    {
        return [
            'new_password' => __('validator.fields.password-confirm'),
        ];
    }

    public function errors(): array
    {
        return [
            'new_password' => [
                'password' => __('validator.rules.password'),
            ],
        ];
    }
}
