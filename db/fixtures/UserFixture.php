<?php

declare(strict_types=1);

namespace Database\Fixtures;

use Database\Factories\UserFactory;
use Project\Models\User;
use Simpler\Components\Database\Fixtures\Fixture;

class UserFixture extends Fixture
{
    public function run(): void
    {
        User::query()->insert(factory(UserFactory::class));
    }
}
