<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionOptionDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_option_descs', function (Blueprint $table) {
            $table->increments('question_option_descs_id');
            $table->integer('question_options_id')->unsigned();
            $table->string('lang_code',2)->comment("en,hi");
            $table->text('option_text');
            $table->timestamps();
            $table->foreign('question_options_id')->references('question_options_id')->on('question_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_option_descs');
    }
}
