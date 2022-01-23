<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamChallengeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_challenge_users', function (Blueprint $table) {
            $table->increments('challenge_user_id');
            $table->integer('exam_challenge_id');
            $table->integer('user_id');
            $table->tinyInteger('is_organiser')->nullable();
            $table->char('challenge_status',1)->comment("A=Accepted, P=Pending, E=Expired")->default('p');
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
        Schema::dropIfExists('exam_challenge_users');
    }
}
