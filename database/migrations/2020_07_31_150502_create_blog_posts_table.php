<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->increments('blog_post_id');
            $table->integer('blog_category_id')->unsigned()->nullable();
            $table->string('slug',100)->nullable();
            $table->string('blog_image',255)->nullable();
            $table->string('seo_meta_title',255)->nullable();
            $table->string('seo_meta_description',255)->nullable();
            $table->tinyInteger('status')->comment("1=Active,0=Inactive")->default(1);
            $table->integer('created_by')->comment("User Id")->nullable();
            $table->integer('updated_by')->comment("User Id")->nullable();
            $table->foreign('blog_category_id')->references('blog_category_id')->on('blog_categories')->onDelete('cascade');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
