<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651006216_create_user_friends_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `user_friends` (
              `id` bigint UNSIGNED NOT NULL,
              `user_id` int UNSIGNED NOT NULL,
              `friend_id` int UNSIGNED NOT NULL,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run("
            ALTER TABLE `user_friends`
            ADD PRIMARY KEY (`id`),
            ADD KEY `user_id` (`user_id`),
            ADD KEY `friend_id` (`friend_id`) USING BTREE;
        ");

        $this->run('ALTER TABLE `user_friends` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;');
        $this->run("
            ALTER TABLE `user_friends`
            ADD CONSTRAINT `wf_user_friends_friend_id` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            ADD CONSTRAINT `wf_user_friends_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    public function down(): void
    {
        $this->run('DROP TABLE user_friends');
    }
}
