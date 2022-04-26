<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651005443_create_groups_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `groups` (
              `id` int UNSIGNED NOT NULL,
              `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
              `image` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
              `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run('ALTER TABLE `groups` ADD PRIMARY KEY (`id`);');
        $this->run('ALTER TABLE `groups` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;');
    }

    public function down(): void
    {
        $this->run('DROP TABLE groups');
    }
}
