<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('questions_id');
            $table->integer('category_id');
            $table->integer('exam_id')->nullable();
            $table->integer('marks');
            $table->string('question_type')->comment("LIVE_TEST, QUIZZES, PRACTICE_TEST, GK_CA");
            $table->tinyInteger('isAssignable')->comment("1=true 0=false")->default(1);
            $table->tinyInteger('status')->comment("1=active 0=Inactive")->default(1);
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
        Schema::dropIfExists('questions');
    }
}
