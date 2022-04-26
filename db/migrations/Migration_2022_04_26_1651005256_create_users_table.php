<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651005256_create_users_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `users` (
              `id` int UNSIGNED NOT NULL,
              `uuid` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
              `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `password` varchar(70) NOT NULL,
              `username` varchar(100) NOT NULL,
              `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
              `verified` tinyint UNSIGNED NOT NULL DEFAULT '0',
              `avatar` varchar(100) DEFAULT NULL,
              `bg` varchar(100) DEFAULT NULL,
              `expired` timestamp NULL DEFAULT NULL,
              `token` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run('ALTER TABLE `users` ADD PRIMARY KEY (`id`);');
        $this->run('ALTER TABLE `users` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;');
    }

    public function down(): void
    {
        $this->run('DROP TABLE users');
    }
}
