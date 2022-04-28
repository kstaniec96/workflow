<?php

declare(strict_types=1);

namespace Project\Http\Validators\User\Posts;

use Simpler\Components\Http\Validator\FormValidator;

class CreateOrEditPostCommentsValidator extends FormValidator
{
    public function rules(): array
    {
        return [
            'message' => 'required|string',
        ];
    }

    public function fields(): array
    {
        return [
            'message' => __('validator.fields.message'),
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
