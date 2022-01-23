<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoubtRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doubt_replies', function (Blueprint $table) {
            $table->increments('reply_id');
            $table->integer('parent_id')->default(0);
            $table->integer('answer_id');
            $table->integer('user_id');
            $table->integer('doubt_id');
            $table->text('doubt_reply')->nullable();
            $table->string('reply_image',100)->nullable();
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
        Schema::dropIfExists('doubt_replies');
    }
}
