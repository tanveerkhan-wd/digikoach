<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_descs', function (Blueprint $table) {
            $table->increments('blog_post_desc_id');
            $table->integer('blog_post_id')->unsigned()->nullable();
            $table->string('lang_code',2)->nullable();
            $table->string('blog_post_title',255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('blog_post_id')->references('blog_post_id')->on('blog_posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_descs');
    }
}
