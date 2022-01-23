<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->increments('blog_category_id');
            $table->string('slug',100)->nullable();
            $table->tinyInteger('status')->comment("1=Active,0=Inactive")->default(1);
            $table->integer('created_by')->comment("User Id")->nullable();
            $table->integer('updated_by')->comment("User Id")->nullable();
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
        Schema::dropIfExists('blog_categories');
    }
}
