<?php

declare(strict_types=1);

namespace Project\Http\Validators\User\Settings;

use Simpler\Components\Http\Validator\FormValidator;

class UpdateSettingsValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'username' => 'required|string',
        ];
    }

    public function fields(): array
    {
        return [
            'username' => __('validator.fields.username'),
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
