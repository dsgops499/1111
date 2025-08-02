SET foreign_key_checks = 0;
SET UNIQUE_CHECKS = 0;

-- tag
ALTER TABLE `tag` ADD `slug` VARCHAR(255) NOT NULL AFTER `name`;
UPDATE tag set slug = id;
SELECT @i := 0;
UPDATE tag SET id=(select @i := @i + 1);
UPDATE manga_tag m, tag t SET tag_id=t.id where t.slug=m.tag_id;

-- author
ALTER TABLE `author` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `manga` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO `author` (`name`, `created_at`) 
SELECT author, now() FROM `manga` WHERE author!="" GROUP BY author;
INSERT INTO `author_manga`(`manga_id`, `author_id`, `type`) 
SELECT m.id, a.id, 1 FROM `manga` m INNER JOIN `author` a ON a.name = m.author;

-- artist  
INSERT INTO `author_manga`(`manga_id`, `author_id`, `type`) 
SELECT m.id, a.id, 2 FROM `manga` m INNER JOIN `author` a ON a.name = m.artist;

INSERT INTO `author` (`name`, `created_at`) 
SELECT m.artist, now() FROM `manga` m WHERE m.artist NOT IN (SELECT a.name FROM `author` a) AND artist!="" GROUP BY artist;

INSERT INTO `author_manga`(`manga_id`, `author_id`, `type`) 
SELECT m.id, a.id, 2 FROM `manga` m INNER JOIN `author` a ON a.name = m.artist AND a.id NOT IN (SELECT author_id FROM `author_manga`);

-- roles
ALTER TABLE `roles` ADD `slug` varchar(255) NOT NULL AFTER `name`;
ALTER TABLE `roles` ADD `permissions` text AFTER `slug`;

UPDATE `roles` SET `slug`='admin',`permissions`='{"dashboard.index":true,"settings.edit_general":true,"settings.edit_themes":true,"blog.manage_posts":true,"blog.manage_pages":true,"gdrive.manage_gdrive":true,"manga.manga.index":true,"manga.manga.create":true,"manga.manga.edit":true,"manga.manga.destroy":true,"manga.manga.hot":true,"manga.chapter.index":true,"manga.chapter.create":true,"manga.chapter.edit":true,"manga.chapter.destroy":true,"manga.chapter.scrap":true,"taxonomies.manage_categories":true,"taxonomies.manage_tags":true,"taxonomies.manage_types":true,"taxonomies.manage_authors":true,"user.users.index":true,"user.users.create":true,"user.users.edit":true,"user.users.destroy":true,"user.roles.index":true,"user.roles.create":true,"user.roles.edit":true,"user.roles.destroy":true,"user.profile":true}' WHERE id=1;
UPDATE `roles` SET `slug`='contributor',`permissions`='{"settings.edit_general":false,"settings.edit_themes":false,"blog.manage_posts":false,"blog.manage_pages":false,"gdrive.manage_gdrive":false,"manga.manga.index":true,"manga.manga.create":true,"manga.manga.edit":true,"manga.manga.destroy":true,"manga.manga.hot":false,"manga.chapter.index":true,"manga.chapter.create":true,"manga.chapter.edit":true,"manga.chapter.destroy":true,"manga.chapter.scrap":false,"taxonomies.manage_categories":false,"taxonomies.manage_tags":false,"taxonomies.manage_types":false,"taxonomies.manage_authors":false,"user.users.index":false,"user.users.create":false,"user.users.edit":false,"user.users.destroy":false,"user.roles.index":false,"user.roles.create":false,"user.roles.edit":false,"user.roles.destroy":false,"user.profile":true}' WHERE id=2;
UPDATE `roles` SET `slug`='guest',`permissions`='{}' WHERE id=3;

-- users
ALTER TABLE `users` ADD `permissions` text AFTER `avatar`;
ALTER TABLE `users` ADD `last_login` timestamp NULL DEFAULT NULL AFTER `permissions`;

INSERT INTO `activations`(`user_id`, `code`, `completed`, `completed_at`, `created_at`) 
SELECT u.id, 'xreOuWRGvXZJmCAtb3fgqc0az9xwbU3Y', 1, now(), u.created_at FROM users u WHERE u.confirmed=1;

INSERT INTO `role_users`(`user_id`, `role_id`, `created_at`)
SELECT user_id, role_id, now() FROM assigned_roles;
