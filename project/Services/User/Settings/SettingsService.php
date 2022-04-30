<?php

declare(strict_types=1);

namespace Project\Services\User\Settings;

use Exception;
use Project\Interfaces\Services\User\Settings\SettingsInterface;
use Project\Models\User;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Enums\State;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;
use Simpler\Components\Security\Hash;
use Simpler\Utils\StringUtil;

class SettingsService implements SettingsInterface
{
    public function update(array $validated): void
    {
        $email = $validated['email'];
        $username = $validated['username'];

        try {
            /** @var User $user */
            $user = Auth::user()->first();

            if (compare($user->email, $email) && compare($user->username, $username)) {
                throw new UnprocessableException(__('app.You want to update unchanged data'));
            }

            if (!compare(Auth::user()->value('email'), $email) && User::query()->where('email', $email)->exists()) {
                throw new UnprocessableException(__('app.The given e-mail address already exists'));
            }

            Auth::user()->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function change(array $validated): void
    {
        $currentPassword = $validated['current_password'];

        try {
            $user = Auth::user();

            if (!Hash::verify($currentPassword, $user->value('password'))) {
                throw new UnprocessableException(__('app.The current password provided is incorrect'));
            }

            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    public function delete(): void
    {
        try {
            $user = Auth::user();

            $user->update([
                'active' => State::INACTIVE,
                'email' => $user->value('email').'_deleted_'.StringUtil::random(5),
            ]);

            Auth::logout();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }
}
