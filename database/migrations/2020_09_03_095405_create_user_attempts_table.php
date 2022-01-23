<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_attempts', function (Blueprint $table) {
            $table->increments('user_attempt_id');
            $table->integer('exam_id');
            $table->string('exams_type',15)->nullable();
            $table->integer('user_id');
            $table->integer('exam_challenge_id')->nullable();
            $table->dateTime('total_time_spent')->nullable();
            $table->integer('total_questions');
            $table->integer('total_marks')->nullable();
            $table->integer('total_attempted');
            $table->integer('total_skipped')->nullable();
            $table->integer('total_correct');
            $table->integer('total_incorrect');
            $table->integer('total_obtain_marks')->nullable();
            $table->float('user_percentage',10,2);
            $table->integer('user_rank')->default(0);
            $table->integer('user_rank_base')->default(0);
            $table->string('attempt_status',15)->comment("PENDING, REGISTERED, COMPLETED, STARTED");
            $table->dateTime('attempted_on');
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
        Schema::dropIfExists('user_attempts');
    }
}
