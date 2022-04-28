<?php

declare(strict_types=1);

namespace Project\Http\Validators\User\OwnGroups;

use Simpler\Components\Http\Validator\FormValidator;

class CreateOrEditOwnGroupsValidator extends FormValidator
{
    protected bool $auth = true;

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function fields(): array
    {
        return [
            'name' => __('validator.fields.name'),
            'description' => __('validator.fields.description'),
        ];
    }

    public function errors(): array
    {
        return [];
    }
}
