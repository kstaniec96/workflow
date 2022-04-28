<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\User\Settings;

interface SettingsInterface
{
    public function update(array $validated): void;

    public function change(array $validated): void;
}
