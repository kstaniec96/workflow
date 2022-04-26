<?php

declare(strict_types=1);

namespace Project\Services\Auth;

use Project\Models\User\UserMainGroup;
use Simpler\Components\Database\DB;
use Simpler\Components\Enums\State;
use Simpler\Components\Exceptions\ServerErrorException;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Exceptions\UnprocessableException;
use Simpler\Components\Facades\Dir;
use Simpler\Components\Security\Hash;
use Exception;
use Faker\Factory;
use Project\Interfaces\Services\Auth\RegisterInterface;
use Project\Models\User;

class RegisterService implements RegisterInterface
{
    public function init(array $validated): void
    {
        DB::beginTransaction();

        try {
            $username = $validated['username'];
            $email = $validated['email'];

            if (User::query()->where('username', $username)->exists()) {
                throw new UnprocessableException(__('app.The given username already exists'));
            }

            if (User::query()->where('email', $email)->exists()) {
                throw new UnprocessableException(__('app.The given e-mail address already exists'));
            }

            $token = Factory::create()->uuid;

            User::query()->insert([
                'username' => $username,
                'password' => Hash::make($validated['password']),
                'email' => $email,
                'uuid' => Factory::create()->unique()->uuid,
                'expired' => date('Y-m-d H:i:s', strtotime('now + 10 minutes')),
                'token' => $token,
            ]);

            $status = mailer([
                'to' => $email,
                'subject' => __('email.register.title'),
                'template' => [
                    'name' => 'register',
                    'params' => [
                        'link' => route('auth.register.verify', ['token' => $token]),
                        'content' => __('email.register.message'),
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

    /**
     * @param null|string $token
     * @return bool
     */
    public function verify(?string $token): bool
    {
        try {
            if (is_null($token)) {
                return false;
            }

            /** @var User $user */
            $user = User::query()->where('token', $token);

            if (($user->value('expired')) < now()->format('Y-m-d H:i:s')) {
                $user->delete();

                return false;
            }
        } catch (Exception $e) {
            throw new ThrowException($e);
        }

        return true;
    }

    public function groups(array $validated): void
    {
        $token = $validated['token'];
        $groups = $validated['groups'];

        DB::beginTransaction();

        try {
            if (!$this->verify($token)) {
                throw new UnprocessableException(__('app.The token has expired'));
            }

            /** @var User $user */
            $user = User::query()->where('token', $token);
            $maxGroupsJoined = env('MAX_NUMBER_OF_MAIN_GROUPS_JOINED');

            if (count($groups) > $maxGroupsJoined) {
                throw new UnprocessableException(
                    __('app.You can join a maximum guide groups', [
                        'groups' => $maxGroupsJoined,
                    ])
                );
            }

            $make = [];

            foreach ($groups as $group) {
                $make[] = [
                    'user_id' => $user->value('id'),
                    'group_id' => $group,
                ];
            }

            // Create user storage
            $path = usersPath($user->value('uuid'));

            Dir::create([
                [$path.'/images'],
                [$path.'/groups/bg'],
                [$path.'/groups/logo'],
            ]);

            // Update user data
            $user->update([
                'verified' => State::YES,
                'expired' => null,
                'token' => null,
            ]);

            // Added user groups
            UserMainGroup::query()->insert($make);

            session()->flash('success', __('app.Your account has been successfully created'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new ThrowException($e);
        }
    }
}
