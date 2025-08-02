SET foreign_key_checks = 0;
SET UNIQUE_CHECKS = 0;

INSERT INTO `menus` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Main', 'main', 1, now(), NULL),
(2, 'Footer', 'footer', 1, now(), NULL),
(3, 'Main with Icons', 'main_icons', 1, now(), NULL);

INSERT INTO `menu_nodes` (`id`, `related_id`, `type`, `url`, `title`, `icon_font`, `css_class`, `target`, `sort_order`, `parent_id`, `menu_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'route', 'front.index', 'Home', NULL, NULL, NULL, 0, NULL, 1, now(), NULL),
(2, NULL, 'route', 'front.manga.list', 'Manga List', NULL, NULL, NULL, 1, NULL, 1, now(), NULL),
(3, NULL, 'route', 'front.manga.latestRelease', 'Latest release', NULL, NULL, NULL, 2, NULL, 1, now(), NULL),
(4, NULL, 'route', 'front.manga.random', 'Random Manga', NULL, NULL, NULL, 3, NULL, 1, now(), NULL),
(5, NULL, 'route', 'front.index', 'Home', NULL, NULL, NULL, 0, NULL, 2, now(), NULL),
(6, NULL, 'route', 'front.manga.list', 'Manga List', NULL, NULL, NULL, 1, NULL, 2, now(), NULL),
(7, NULL, 'route', 'front.manga.latestRelease', 'Latest release', NULL, NULL, NULL, 2, NULL, 2, now(), NULL),
(8, NULL, 'route', 'front.index', 'Home', 'fa fa-home', NULL, NULL, 0, NULL, 3, now(), NULL),
(9, NULL, 'route', 'front.manga.list', 'Manga List', 'fa fa-th-large', NULL, NULL, 1, NULL, 3, now(), NULL),
(10, NULL, 'route', 'front.manga.latestRelease', 'Latest release', 'fa fa-list', NULL, NULL, 2, NULL, 3, now(), NULL),
(11, NULL, 'route', 'front.manga.random', 'Random Manga', 'fa fa-random', NULL, NULL, 3, NULL, 3, now(), NULL);

INSERT INTO `options` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(24, 'site.captcha', '', '2017-12-19 23:01:10', NULL),
(25, 'site.theme.options', '{\"reader_theme\":\"darkly\",\"main_menu\":\"1\",\"footer_menu\":\"2\",\"boxed\":\"1\",\"logo\":null,\"icon\":null}', NULL, NULL);
