<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMangaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
        
        Schema::create('comictype', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
        
        Schema::create('manga', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('otherNames')->nullable();
            $table->string('releaseDate')->nullable();
            $table->text('summary')->nullable();
            $table->boolean('cover')->nullable();
            $table->boolean('hot')->nullable();
            $table->boolean('caution')->nullable()->default(0);
            $table->integer('views')->nullable()->default(0);
            $table->integer('rate')->nullable()->default(0);
            $table->text('bulkStatus')->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->index('slug');
            $table->foreign('type_id')->references('id')->on('comictype');
            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
        
        Schema::create('chapter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('manga_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->foreign('manga_id')->references('id')->on('manga');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
        
        Schema::create('page', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slug');
            $table->string('image')->nullable();
            $table->boolean('external')->default(0);
            $table->integer('chapter_id')->unsigned();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->foreign('chapter_id')->references('id')->on('chapter');
        });
        
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('slug');
        });

        Schema::create('category_manga', function (Blueprint $table) {
            $table->integer('manga_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->engine = 'InnoDB';
            $table->primary(['manga_id', 'category_id']);
            $table->foreign('manga_id')->references('id')->on('manga')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
        });
        
        Schema::create('tag', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('manga_tag', function (Blueprint $table) {
            $table->integer('manga_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->engine = 'InnoDB';
            $table->primary(['manga_id', 'tag_id']);
            $table->foreign('manga_id')->references('id')->on('manga')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tag')->onDelete('cascade');
        });
        
        Schema::create('author', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('author_manga', function (Blueprint $table) {
            $table->integer('manga_id')->unsigned();
            $table->integer('author_id')->unsigned();
            $table->boolean('type')->default(1);

            $table->engine = 'InnoDB';
            $table->primary(['manga_id', 'author_id', 'type']);
            $table->foreign('manga_id')->references('id')->on('manga')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('author')->onDelete('cascade');
        });
        
        Schema::create('item_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->tinyInteger('score')->default(1);
            $table->timestamp('added_on');
            $table->string('ip_address');

            $table->engine = 'InnoDB';
            $table->index('item_id');
            $table->index('ip_address');
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
        Schema::dropIfExists('author_manga');
        Schema::dropIfExists('author');
        Schema::dropIfExists('manga_tag');
        Schema::dropIfExists('tag');
        Schema::dropIfExists('category_manga');
        Schema::dropIfExists('category');
        Schema::dropIfExists('status');
        Schema::dropIfExists('comictype');
        Schema::dropIfExists('page');
        Schema::dropIfExists('chapter');
        Schema::dropIfExists('manga');
        Schema::dropIfExists('item_ratings');
        Schema::enableForeignKeyConstraints();
    }
}
