<?php

namespace Modules\Base\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $main_id = \DB::table('menus')->insertGetId([
            'title' => 'Main',
            'slug' => 'main',
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);
        $footer_id = \DB::table('menus')->insertGetId([
            'title' => 'Footer',
            'slug' => 'footer',
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);
        $main_icons_id = \DB::table('menus')->insertGetId([
            'title' => 'Main with Icons',
            'slug' => 'main_icons',
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('options')->insert([
            'key' => 'site.name',
            'value' => 'My Manga Reader',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.slogan',
            'value' => 'Read Manga Online for Free',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.description',
            'value' => 'Read your favorite manga scans and scanlations online at my Manga Reader. Read Manga Online, Absolutely Free and Updated Daily.',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.theme',
            'value' => 'default.united',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.lang',
            'value' => 'en',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.subscription',
            'value' => '{"subscribe":"false","admin_confirm":"false","email_confirm":"false","default_role":"3","address":"admin@mydomain.com","name":"admin","mailing":"sendmail","host":"","port":"","username":"","password":""}',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.orientation',
            'value' => 'ltr',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.comment',
            'value' => '',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.pagination',
            'value' => '{"homepage":"40","mangalist":"20","latest_release":"40","news_homepage":"10","newslist":"15"}',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.widgets',
            'value' => '{"0":{"type":"site_description"},"1":{"type":"top_rates","title":"Top Manga","number":"10"}}',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.cache',
            'value' => '{"reader":"120"}',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.gdrive',
            'value' => '',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.captcha',
            'value' => '',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'site.theme.options',
            'value' => '{"reader_theme":"darkly","main_menu":"'.$main_id.'","footer_menu":"'.$footer_id.'","boxed":"1","logo":null,"icon":null}',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'manga.options',
            'value' => '',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'reader.type',
            'value' => 'ppp',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'reader.mode',
            'value' => 'noreload',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'storage.type',
            'value' => 'server',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'seo.keywords',
            'value' => 'manga,read manga,read manga online,manga online,online manga,manga reader, manga scans,english manga,naruto manga,bleach manga, one piece manga,manga chapter,read free manga,free manga,read free manga online,manga viewer',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'seo.google.analytics',
            'value' => '',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'seo.google.webmaster',
            'value' => '',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'seo.title',
            'value' => 'My Manga Reader - Read Manga Online for Free',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'seo.description',
            'value' => 'Read your favorite manga scans and scanlations online at my Manga Reader. Read Manga Online, Absolutely Free and Updated Daily.',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('options')->insert([
            'key' => 'seo.advanced',
            'value' => '{"info":{"title":{"value":"%manga_name% by %manga_author% - Info Page"},"description":{"value":"%manga_description%"},"keywords":{"value":"%manga_name%, %manga_author%, %manga_categories%"}},"reader":{"title":{"value":"%manga_name% Chapter %chapter_number% - Page %page_number%"},"description":{"value":"%manga_name% Chapter %chapter_number%:  %chapter_title% - Page %page_number%"},"keywords":{"value":"%manga_name%, %manga_author%, %manga_categories%"}},"news":{"title":{"value":"%post_title%"},"description":{"value":"%post_content%"},"keywords":{"value":"%post_keywords%"}},"mangalist":{"title":{"global":"1","value":""},"description":{"global":"1","value":""},"keywords":{"global":"1","value":""}},"latestrelease":{"title":{"global":"1","value":""},"description":{"global":"1","value":""},"keywords":{"global":"1","value":""}},"latestnews":{"title":{"global":"1","value":""},"description":{"global":"1","value":""},"keywords":{"global":"1","value":""}}}',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.index',
            'title' => 'Home',
            'sort_order' => 0,
            'menu_id' => $main_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.list',
            'title' => 'Manga List',
            'sort_order' => 1,
            'menu_id' => $main_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.latestRelease',
            'title' => 'Latest release',
            'sort_order' => 2,
            'menu_id' => $main_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.random',
            'title' => 'Random Manga',
            'sort_order' => 3,
            'menu_id' => $main_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.index',
            'title' => 'Home',
            'sort_order' => 0,
            'menu_id' => $footer_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.list',
            'title' => 'Manga List',
            'sort_order' => 1,
            'menu_id' => $footer_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.latestRelease',
            'title' => 'Latest release',
            'sort_order' => 2,
            'menu_id' => $footer_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.index',
            'title' => 'Home',
            'icon_font' => 'fa fa-home',
            'sort_order' => 0,
            'menu_id' => $main_icons_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.list',
            'title' => 'Manga List',
            'icon_font' => 'fa fa-th-large',
            'sort_order' => 1,
            'menu_id' => $main_icons_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.latestRelease',
            'title' => 'Latest release',
            'icon_font' => 'fa fa-list',
            'sort_order' => 2,
            'menu_id' => $main_icons_id,
            'created_at' => Carbon::now(),
        ]);
        \DB::table('menu_nodes')->insert([
            'type' => 'route',
            'url' => 'front.manga.random',
            'title' => 'Random Manga',
            'icon_font' => 'fa fa-random',
            'sort_order' => 3,
            'menu_id' => $main_icons_id,
            'created_at' => Carbon::now(),
        ]);
    }
}
