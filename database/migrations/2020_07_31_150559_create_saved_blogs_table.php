<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_blogs', function (Blueprint $table) {
            $table->increments('saved_blog_id');
            $table->integer('blog_post_id')->unsigned()->nullable();
            $table->integer('blog_category_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('blog_post_id')->references('blog_post_id')->on('blog_posts')->onDelete('cascade');
            $table->foreign('blog_category_id')->references('blog_category_id')->on('blog_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_blogs');
    }
}
