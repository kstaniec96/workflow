<?php

declare(strict_types=1);

namespace Database\Factories;

use Faker\Factory;
use Simpler\Components\Database\Factories\AbstractFactory;
use Simpler\Components\Security\Hash;

class UserFactory extends AbstractFactory
{
    public function handle(): array
    {
        return [
            'username' => 'username',
            'password' => Hash::make('test1'),
            'email' => 'test@example.com',
            'uuid' => Factory::create()->unique()->uuid,
        ];
    }
}
