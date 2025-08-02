<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $user_id = \DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@yourdomain.com',
            'password' => '$2y$10$68L/jA405KNKD5Au7PIqiuNfssuwEfG1hlpfaRqG7p5ZwNgGIZ1NS',
            'created_at' => Carbon::now(),
        ]);
        
        $roles_id = \DB::table('roles')->insertGetId([
            'name' => 'Administrator',
            'slug' => 'admin',
            'permissions' => '{"dashboard.index":true,"settings.edit_general":true,"settings.edit_themes":true,"blog.manage_posts":true,"blog.manage_pages":true,"gdrive.manage_gdrive":true,"manga.manga.index":true,"manga.manga.create":true,"manga.manga.edit":true,"manga.manga.destroy":true,"manga.manga.hot":true,"manga.chapter.index":true,"manga.chapter.create":true,"manga.chapter.edit":true,"manga.chapter.destroy":true,"manga.chapter.scrap":true,"taxonomies.manage_categories":true,"taxonomies.manage_tags":true,"taxonomies.manage_types":true,"taxonomies.manage_authors":true,"user.users.index":true,"user.users.create":true,"user.users.edit":true,"user.users.destroy":true,"user.roles.index":true,"user.roles.create":true,"user.roles.edit":true,"user.roles.destroy":true,"user.profile":true}',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('roles')->insert([
            'name' => 'Contributor',
            'slug' => 'contributor',
            'permissions' => '{"settings.edit_general":false,"settings.edit_themes":false,"blog.manage_posts":false,"blog.manage_pages":false,"gdrive.manage_gdrive":false,"manga.manga.index":true,"manga.manga.create":true,"manga.manga.edit":true,"manga.manga.destroy":true,"manga.manga.hot":false,"manga.chapter.index":true,"manga.chapter.create":true,"manga.chapter.edit":true,"manga.chapter.destroy":true,"manga.chapter.scrap":false,"taxonomies.manage_categories":false,"taxonomies.manage_tags":false,"taxonomies.manage_types":false,"taxonomies.manage_authors":false,"user.users.index":false,"user.users.create":false,"user.users.edit":false,"user.users.destroy":false,"user.roles.index":false,"user.roles.create":false,"user.roles.edit":false,"user.roles.destroy":false,"user.profile":true}',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('roles')->insert([
            'name' => 'Guest',
            'slug' => 'guest',
            'permissions' => '{"settings.edit_general":false,"settings.edit_themes":false,"blog.manage_posts":false,"blog.manage_pages":false,"gdrive.manage_gdrive":false,"manga.manga.index":false,"manga.manga.create":false,"manga.manga.edit":false,"manga.manga.destroy":false,"manga.manga.hot":false,"manga.chapter.index":false,"manga.chapter.create":false,"manga.chapter.edit":false,"manga.chapter.destroy":false,"manga.chapter.scrap":false,"taxonomies.manage_categories":false,"taxonomies.manage_tags":false,"taxonomies.manage_types":false,"taxonomies.manage_authors":false,"user.users.index":false,"user.users.create":false,"user.users.edit":false,"user.users.destroy":false,"user.roles.index":false,"user.roles.create":false,"user.roles.edit":false,"user.roles.destroy":false,"user.profile":true}',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('role_users')->insert([
            'user_id' => $user_id,
            'role_id' => $roles_id,
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('activations')->insert([
            'user_id' => $user_id,
            'code' => 'xreOuWRGvXZJmCAtb3fgqc0az9xwbU3Y',
            'completed' => 1,
            'completed_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
    }
}
