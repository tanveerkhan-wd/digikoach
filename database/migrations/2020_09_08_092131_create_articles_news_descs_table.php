<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesNewsDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_news_descs', function (Blueprint $table) {
            $table->increments('articles_news_descs_id');
            $table->integer('articles_news_id')->unsigned();
            $table->string('lang_code',2);
            $table->string('article_title',255);
            $table->text('article_body');
            $table->timestamps();
            $table->foreign('articles_news_id')->references('articles_news_id')->on('articles_news')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_news_descs');
    }
}
