<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExamResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_exam_responses', function (Blueprint $table) {
            $table->increments('exam_responses_id');
            $table->integer('user_id');
            $table->integer('exams_id');
            $table->integer('user_attempt_id');
            $table->integer('exam_questions_id');
            $table->integer('questions_id');
            $table->integer('option_id');
            $table->tinyInteger('is_valid');
            $table->integer('obtain_mark');
            $table->integer('total_time_spent')->nullable();
            $table->tinyInteger('attempt_status')->comment("1=Attempt,0=NotAttempt")->default(1);
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
        Schema::dropIfExists('user_exam_responses');
    }
}
