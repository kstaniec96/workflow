<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_26_1651006121_create_posts_likes_table extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `post_likes` (
              `id` bigint UNSIGNED NOT NULL,
              `user_id` int UNSIGNED NOT NULL,
              `post_id` bigint UNSIGNED NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run("
            ALTER TABLE `post_likes`
            ADD PRIMARY KEY (`id`),
            ADD KEY `user_id` (`user_id`),
            ADD KEY `post_id` (`post_id`);
        ");

        $this->run('ALTER TABLE `post_likes` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->run("
            ALTER TABLE `post_likes`
            ADD CONSTRAINT `wf_post_likes_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            ADD CONSTRAINT `wf_post_likes_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    public function down(): void
    {
        $this->run('DROP TABLE post_likes');
    }
}
