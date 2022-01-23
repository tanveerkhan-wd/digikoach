<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_news', function (Blueprint $table) {
            $table->increments('articles_news_id');
            $table->string('meta_title',255);
            $table->text('meta_description');
            $table->tinyInteger('status')->comment("1=Active,0=Inactive")->default(1);
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
        Schema::dropIfExists('articles_news');
    }
}
