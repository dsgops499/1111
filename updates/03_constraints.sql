SET foreign_key_checks = 0;
SET UNIQUE_CHECKS = 0;

-- ids
ALTER TABLE `ad` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ad_placement` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ad_placement` CHANGE `ad_id` `ad_id` INT(10) UNSIGNED;
ALTER TABLE `ad_placement` CHANGE `placement_id` `placement_id` INT(10) UNSIGNED;

ALTER TABLE `bookmarks` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `bookmarks` CHANGE `manga_id` `manga_id` INT(10) UNSIGNED;
ALTER TABLE `bookmarks` CHANGE `chapter_id` `chapter_id` INT(10) UNSIGNED DEFAULT NULL;
ALTER TABLE `bookmarks` CHANGE `page_id` `page_id` INT(10) UNSIGNED DEFAULT NULL;
ALTER TABLE `bookmarks` CHANGE `user_id` `user_id` INT(10) UNSIGNED;

ALTER TABLE `category` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `category_manga` CHANGE `manga_id` `manga_id` INT(10) UNSIGNED;
ALTER TABLE `category_manga` CHANGE `category_id` `category_id` INT(10) UNSIGNED;

ALTER TABLE `chapter` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `chapter` CHANGE `manga_id` `manga_id` INT(10) UNSIGNED;
ALTER TABLE `chapter` CHANGE `user_id` `user_id` INT(10) UNSIGNED;

ALTER TABLE `comictype` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `comments` CHANGE `post_id` `post_id` INT(10) UNSIGNED;
ALTER TABLE `comments` CHANGE `parent_comment` `parent_comment` INT(10) UNSIGNED;
ALTER TABLE `comments` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL;

ALTER TABLE `manga` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `manga` CHANGE `status_id` `status_id` INT(10) UNSIGNED DEFAULT NULL;
ALTER TABLE `manga` CHANGE `type_id` `type_id` INT(10) UNSIGNED DEFAULT NULL;
ALTER TABLE `manga` CHANGE `user_id` `user_id` INT(10) UNSIGNED;

ALTER TABLE `page` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `page` CHANGE `chapter_id` `chapter_id` INT(10) UNSIGNED;

ALTER TABLE `placement` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `posts` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `posts` CHANGE `user_id` `user_id` INT(10) UNSIGNED;
ALTER TABLE `posts` CHANGE `manga_id` `manga_id` INT(10) UNSIGNED;

ALTER TABLE `status` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tag` CHANGE `id` `id` INT(10) UNSIGNED AUTO_INCREMENT;

ALTER TABLE `manga_tag` CHANGE `manga_id` `manga_id` INT(10) UNSIGNED;
ALTER TABLE `manga_tag` CHANGE `tag_id` `tag_id` INT(10) UNSIGNED;

ALTER TABLE `roles` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `manga` ADD INDEX(`slug`);

-- index
ALTER TABLE `author_manga`
  ADD PRIMARY KEY (`manga_id`,`author_id`,`type`),
  ADD KEY `author_manga_author_id_foreign` (`author_id`);

ALTER TABLE `jobs`
  ADD KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`);

ALTER TABLE `menu_nodes`
  ADD KEY `menu_nodes_menu_id_foreign` (`menu_id`),
  ADD KEY `menu_nodes_parent_id_foreign` (`parent_id`);

ALTER TABLE `notifications`
  ADD KEY `notifications_user_id_foreign` (`user_id`);

ALTER TABLE `notif_settings`
  ADD UNIQUE KEY `notif_settings_user_id_unique` (`user_id`);

ALTER TABLE `page_cms`
  ADD KEY `page_cms_user_id_foreign` (`user_id`);

ALTER TABLE `persistences`
  ADD UNIQUE KEY `persistences_code_unique` (`code`);

ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`);

ALTER TABLE `throttle`
  ADD KEY `throttle_user_id_index` (`user_id`);

-- constraints
ALTER TABLE `author_manga`
  ADD CONSTRAINT `author_manga_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_manga_manga_id_foreign` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE;

ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_manga_id_foreign` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `category_manga`
  ADD CONSTRAINT `category_manga_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_manga_manga_id_foreign` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE;

ALTER TABLE `chapter`
  ADD CONSTRAINT `chapter_manga_id_foreign` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`),
  ADD CONSTRAINT `chapter_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_comment_foreign` FOREIGN KEY (`parent_comment`) REFERENCES `comments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `manga`
  ADD CONSTRAINT `manga_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `manga_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `comictype` (`id`),
  ADD CONSTRAINT `manga_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `manga_tag`
  ADD CONSTRAINT `manga_tag_manga_id_foreign` FOREIGN KEY (`manga_id`) REFERENCES `manga` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manga_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE;

ALTER TABLE `menu_nodes`
  ADD CONSTRAINT `menu_nodes_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_nodes_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_nodes` (`id`) ON DELETE SET NULL;

ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `notif_settings`
  ADD CONSTRAINT `notif_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `page`
  ADD CONSTRAINT `page_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`);

ALTER TABLE `page_cms`
  ADD CONSTRAINT `page_cms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

