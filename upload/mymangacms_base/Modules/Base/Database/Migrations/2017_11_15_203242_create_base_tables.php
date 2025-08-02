<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('key');
        });
        
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            
            $table->engine = 'InnoDB';
        });
        
        Schema::create('menu_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('related_id')->unsigned()->nullable();
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('title')->nullable();
            $table->string('icon_font')->nullable();
            $table->string('css_class')->nullable();
            $table->string('target')->nullable();
            $table->integer('sort_order')->unsigned()->default(0);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('menu_id')->unsigned();
            $table->timestamps();
            
            $table->engine = 'InnoDB';
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('menu_nodes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('menu_nodes');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('options');
        Schema::enableForeignKeyConstraints();
    }
}
