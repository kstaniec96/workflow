<?php

declare(strict_types=1);

namespace Project\Services\Auth;

use Simpler\Components\Auth\Auth;
use Simpler\Components\Auth\AuthToken;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;
use Simpler\Components\Security\Hash;
use Exception;
use Project\Interfaces\Services\Auth\LoginInterface;
use Project\Models\User;

class LoginService implements LoginInterface
{
    public function api(array $validated): array
    {
        try {
            $user = User::query()->where('email', $validated['email'])->first();

            if (is_null($user) || !Hash::verify($validated['password'], $user->password)) {
                throw new UnprocessableException(__('app.The given e-mail address or password is incorrect'));
            }

            if (!$user->active) {
                throw new UnprocessableException(__('app.The account is inactive'));
            }

            if (!$user->verified) {
                throw new UnprocessableException(__('app.The account has not yet been verified'));
            }

            Auth::login($user);
            $authToken = AuthToken::generate($user);

            return [
                'accessToken' => $authToken->token(),
                'expire' => $authToken->expire(),
            ];
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }
}
