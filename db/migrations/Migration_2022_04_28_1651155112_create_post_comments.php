<?php

declare(strict_types=1);

namespace Database\Migrations;

use Simpler\Components\Database\Migrations\Migration;

class Migration_2022_04_28_1651155112_create_post_comments extends Migration
{
    public function up(): void
    {
        $this->run("
            CREATE TABLE `post_comments` (
              `id` bigint UNSIGNED NOT NULL,
              `user_id` int UNSIGNED NOT NULL,
              `post_id` bigint UNSIGNED NOT NULL,
              `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->run("
            ALTER TABLE `post_comments`
            ADD PRIMARY KEY (`id`),
            ADD KEY `user_id` (`user_id`),
            ADD KEY `post_id` (`post_id`);
        ");

        $this->run('ALTER TABLE `post_comments` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->run("
            ALTER TABLE `post_comments`
            ADD CONSTRAINT `wf_post_comments_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            ADD CONSTRAINT `wf_post_comments_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    public function down(): void
    {
        $this->run('DROP TABLE `post_comments`');
    }
}
