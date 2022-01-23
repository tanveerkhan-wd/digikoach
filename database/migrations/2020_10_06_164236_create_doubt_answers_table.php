<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoubtAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doubt_answers', function (Blueprint $table) {
            $table->increments('answer_id');
            $table->integer('parent_id')->default(0);
            $table->integer('user_id');
            $table->integer('doubt_id');
            $table->text('doubt_answer')->nullable();
            $table->integer('total_answers')->default(0);
            $table->string('answer_image', 100)->nullable();
            $table->integer('answer_upvote')->default(0);
            $table->integer('total_replies')->default(0);
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
        Schema::dropIfExists('doubt_answers');
    }
}
