<?php

declare(strict_types=1);

namespace Project\Http\Controllers\User;

use Project\Http\Validators\User\Settings\ChangePasswordSettingsValidator;
use Project\Http\Validators\User\Settings\UpdateSettingsValidator;
use Project\Services\User\Settings\SettingsService;
use Simpler\Components\Auth\Auth;

class SettingsController extends UserController
{
    public function index(): string
    {
        $user = Auth::user()->first();

        return $this->render('user/settings', compact('user'));
    }

    public function update(UpdateSettingsValidator $validator, SettingsService $settingsService): string
    {
        $settingsService->update($validator->validated());

        return $this->json();
    }

    public function change(ChangePasswordSettingsValidator $validator, SettingsService $settingsService): string
    {
        $settingsService->change($validator->validated());

        return $this->json();
    }

    public function delete(SettingsService $settingsService): string
    {
        $settingsService->delete();

        return $this->json();
    }
}
