<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
            $table->text('content')->nullable();
            $table->boolean('status')->default(0);
            $table->string('keywords')->nullable();
            $table->integer('manga_id')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        Schema::create('page_cms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
            $table->text('content')->nullable();
            $table->boolean('status')->default(0);
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('posts');
        Schema::dropIfExists('page_cms');
        Schema::enableForeignKeyConstraints();
    }
}
