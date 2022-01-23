<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSavedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_saved_items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->integer('user_id');
            $table->string('item_type',30)->comment("EXAM, ARTICLE, POST, DOUBT, DOUBT_UPVOTE, DOUBT_ANS_UPVOTE");
            $table->integer('item_type_id')->comment("exam_id, article_id");
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
        Schema::dropIfExists('user_saved_items');
    }
}
