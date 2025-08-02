<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bloc_id')->nullable();
            $table->text('code')->nullable();
            
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        
        Schema::create('placement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page')->nullable();
            
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        
        Schema::create('ad_placement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id')->unsigned();
            $table->integer('placement_id')->unsigned();
            $table->string('placement');
            
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad');
        Schema::dropIfExists('placement');
        Schema::dropIfExists('ad_placement');
    }
}
