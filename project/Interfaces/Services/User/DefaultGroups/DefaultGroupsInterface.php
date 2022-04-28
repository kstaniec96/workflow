<?php

declare(strict_types=1);

namespace Project\Interfaces\Services\User\DefaultGroups;

interface DefaultGroupsInterface
{
    public function add(int $id): void;

    public function remove(int $id): void;

    public static function joinedGroup(int $groupId): bool;
}
