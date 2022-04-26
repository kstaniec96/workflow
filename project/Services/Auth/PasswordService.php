<?php

declare(strict_types=1);

namespace Project\Services\Auth;

use Exception;
use Faker\Factory;
use Project\Interfaces\Services\Auth\PasswordInterface;
use Project\Models\User;
use Simpler\Components\Database\DB;
use Simpler\Components\Exceptions\ServerErrorException;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;
use Simpler\Components\Security\Hash;

class PasswordService implements PasswordInterface
{
    public function init(array $validated): void
    {
        $email = $validated['email'];
        DB::beginTransaction();

        try {
            /** @var User $user */
            $user = User::query()->where('email', $email);

            if (!$user->exists()) {
                throw new UnprocessableException(__('app.The given e-mail address does not exist'));
            }

            /** @var RegisterService $registerService */
            $registerService = instance(RegisterService::class);

            if ($registerService->verify($user->value('token'))) {
                throw new UnprocessableException(__('app.You cannot change your password yet'));
            }

            $token = Factory::create()->uuid;

            // Update user token
            $user->update([
                'expired' => date('Y-m-d H:i:s', strtotime('now + 10 minutes')),
                'token' => $token,
            ]);

            $status = mailer([
                'to' => $email,
                'subject' => __('email.password.title'),
                'template' => [
                    'name' => 'password',
                    'params' => [
                        'link' => route('auth.password.verify', ['token' => $token]),
                        'content' => __('email.password.message'),
                    ],
                ],
            ]);

            if (!$status) {
                throw new ServerErrorException(__('app.The e-mail was not sent'));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new ThrowException($e);
        }
    }

    public function change(array $validated): void
    {
        $token = $validated['token'];
        $password = $validated['password'];
        $passwordConfirmed = $validated['password_confirm'];

        try {
            /** @var RegisterService $registerService */
            $registerService = instance(RegisterService::class);

            if (!$registerService->verify($token)) {
                throw new UnprocessableException(__('app.The token has expired'));
            }

            if (!compare($password, $passwordConfirmed)) {
                throw new UnprocessableException(__('app.The passwords provided do not match'));
            }

            // Update user password
            User::query()->where('token', $token)->update([
                'password' => Hash::make($password),
                'expired' => null,
                'token' => null,
            ]);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }
}
