<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_descs', function (Blueprint $table) {
            $table->increments('question_descs_id');
            $table->integer('questions_id')->unsigned();
            $table->string('lang_code',2)->comment("en,hi");
            $table->text('question_text');
            $table->text('solution_text');
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
        Schema::dropIfExists('question_descs');
    }
}
