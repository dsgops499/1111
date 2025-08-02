<?php

namespace Modules\Manga\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MangaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        \DB::table('status')->insert([
            'label' => 'Ongoing',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('status')->insert([
            'label' => 'Complete',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('category')->insert([
            'name' => 'Action',
            'slug' => 'action',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Adventure',
            'slug' => 'adventure',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Comedy',
            'slug' => 'comedy',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Doujinshi',
            'slug' => 'doujinshi',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Drama',
            'slug' => 'drama',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Ecchi',
            'slug' => 'ecchi',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Fantasy',
            'slug' => 'fantasy',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Gender Bender',
            'slug' => 'gender-bender',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Harem',
            'slug' => 'harem',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Historical',
            'slug' => 'historical',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Horror',
            'slug' => 'horror',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Josei',
            'slug' => 'josei',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Martial Arts',
            'slug' => 'martial-arts',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Mature',
            'slug' => 'mature',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Mecha',
            'slug' => 'mecha',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Mystery',
            'slug' => 'mystery',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'One Shot',
            'slug' => 'one-shot',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Psychological',
            'slug' => 'psychological',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Romance',
            'slug' => 'romance',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'School Life',
            'slug' => 'school-life',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Sci-fi',
            'slug' => 'sci-fi',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Seinen',
            'slug' => 'seinen',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Shoujo',
            'slug' => 'shoujo',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Shoujo Ai',
            'slug' => 'shoujo-ai',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Shounen',
            'slug' => 'shounen',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Shounen Ai',
            'slug' => 'shounen-ai',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Slice of Life',
            'slug' => 'slice-of-life',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Sports',
            'slug' => 'sports',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Supernatural',
            'slug' => 'supernatural',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Tragedy',
            'slug' => 'tragedy',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Yaoi',
            'slug' => 'yaoi',
            'created_at' => Carbon::now(),
        ]);
        \DB::table('category')->insert([
            'name' => 'Yuri',
            'slug' => 'yuri',
            'created_at' => Carbon::now(),
        ]);
    }
}
