<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_media', function (Blueprint $table) {
            $table->increments('media_id');
            $table->integer('media_int_id')->nullable();
            $table->string('media_int_type',30);
            $table->string('lang_code',2);
            $table->string('media_file',100)->comment("QUESTION, SOLUTION, OPTION");
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
        Schema::dropIfExists('question_media');
    }
}
