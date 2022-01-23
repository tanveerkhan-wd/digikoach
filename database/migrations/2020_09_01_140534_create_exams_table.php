<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('exam_id');
            $table->integer('category_id');
            $table->string('exams_type',15)->comment('LIVE_TEST, QUIZZES, PRACTICE_TEST, GK_CA');
            $table->integer('exam_duration')->nullable();
            $table->dateTime('exam_starts_on')->nullable();
            $table->dateTime('exam_ends_on')->nullable();
            $table->dateTime('result_date')->nullable();
            $table->integer('total_questions');
            $table->integer('total_marks');
            $table->tinyInteger('result_announce_status')->comment("0=Not Announced,1=Announced")->default(0);
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
        Schema::dropIfExists('exams');
    }
}
