<?php

declare(strict_types=1);

namespace Project\Http\Validators\Auth\Register;

use Simpler\Components\Http\Validator\FormValidator;

class RegisterSecondStepValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'groups' => 'required|array',
            'token' => 'required|string',
        ];
    }

    public function fields(): array
    {
        return [
            'groups' => 'grupy',
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
