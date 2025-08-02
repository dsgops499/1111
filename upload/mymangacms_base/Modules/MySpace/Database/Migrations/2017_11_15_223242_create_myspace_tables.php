<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMySpaceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->integer('manga_id')->unsigned();
            $table->integer('chapter_id')->unsigned()->nullable();
            $table->integer('page_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->foreign('manga_id')->references('id')->on('manga')->onDelete('cascade');
            $table->foreign('chapter_id')->references('id')->on('chapter')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('page')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment')->nullable();
            $table->integer('post_id')->unsigned()->nullable();
            $table->string('post_type')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('parent_comment')->unsigned()->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_comment')->references('id')->on('comments')->onDelete('set null');
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
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('comments');
        Schema::enableForeignKeyConstraints();
    }
}
