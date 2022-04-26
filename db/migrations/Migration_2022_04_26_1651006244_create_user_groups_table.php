<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651006244_create_user_groups_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `user_groups` (
              `id` bigint UNSIGNED NOT NULL,
              `user_id` int UNSIGNED NOT NULL,
              `group_id` int UNSIGNED NOT NULL,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run("
            ALTER TABLE `user_groups`
            ADD PRIMARY KEY (`id`),
            ADD KEY `user_id` (`user_id`),
            ADD KEY `group_id` (`group_id`);
        ");

        $this->run('ALTER TABLE `user_groups` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;');
        $this->run("
            ALTER TABLE `user_groups`
            ADD CONSTRAINT `wf_user_groups_group_id_fk` FOREIGN KEY (`group_id`) REFERENCES `user_created_groups` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            ADD CONSTRAINT `wf_user_groups_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    public function down(): void
    {
        $this->run('DROP TABLE user_groups');
    }
}
