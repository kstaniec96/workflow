<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651006164_create_user_created_groups_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `user_created_groups` (
              `id` int UNSIGNED NOT NULL,
              `user_id` int UNSIGNED NOT NULL,
              `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
              `logo` varchar(100) DEFAULT NULL,
              `bg` varchar(100) DEFAULT NULL,
              `disabled` tinyint UNSIGNED NOT NULL DEFAULT 0,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run('ALTER TABLE `user_created_groups` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);');
        $this->run('ALTER TABLE `user_created_groups` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;');

        $this->run("
            ALTER TABLE `user_created_groups` ADD CONSTRAINT `wf_user_created_groups_user_id_fk` FOREIGN KEY (`user_id`) 
            REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    public function down(): void
    {
        $this->run('DROP TABLE  user_created_groups');
    }
}
