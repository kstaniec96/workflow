<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651006080_create_posts_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `posts` (
              `id` bigint UNSIGNED NOT NULL,
              `user_id` int UNSIGNED NOT NULL,
              `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run('ALTER TABLE `posts` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);');
        $this->run('ALTER TABLE `posts` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;');

        $this->run("
            ALTER TABLE `posts`
            ADD CONSTRAINT `wf_posts_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
            ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    public function down(): void
    {
        $this->run('DROP TABLE posts');
    }
}
