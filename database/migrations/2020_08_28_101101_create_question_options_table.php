<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->increments('question_options_id');
            $table->integer('questions_id')->unsigned();
            $table->tinyInteger('option_order')->nullable();
            $table->tinyInteger('is_valid')->comment("1=Correct 0=InCorrect")->default(0);
            $table->timestamps();
            $table->foreign('questions_id')->references('questions_id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_options');
    }
}
