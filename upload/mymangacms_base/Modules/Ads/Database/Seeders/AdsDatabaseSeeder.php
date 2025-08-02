<?php

namespace Modules\Ads\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class AdsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        \DB::table('placement')->insert([
            'page' => 'READER',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('placement')->insert([
            'page' => 'HOMEPAGE',
            'created_at' => Carbon::now(),
        ]);
        
        \DB::table('placement')->insert([
            'page' => 'MANGAINFO',
            'created_at' => Carbon::now(),
        ]);
    }
}
