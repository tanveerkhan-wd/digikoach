<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories_descs', function (Blueprint $table) {
            $table->increments('blog_categories_desc_id');
            $table->integer('blog_category_id')->unsigned()->nullable();
            $table->string('lang_code',2)->nullable();
            $table->string('blog_category_title',255)->nullable();
            $table->timestamps();
            $table->foreign('blog_category_id')->references('blog_category_id')->on('blog_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_categories_descs');
    }
}
