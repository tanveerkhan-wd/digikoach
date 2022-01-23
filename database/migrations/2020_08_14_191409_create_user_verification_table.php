<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->increments('verification_id');
            $table->integer('user_id')->nullable();
            $table->string('verification_type', 30)->comment('NEW_EMAIL / NEW_MOBILE / UPDATE_EMAIL / UPDATE_MOBILE / FORGOT_PASSWORD');
            $table->string('verification_value', 255);
            $table->string('verification_otp', 30);
            $table->timestamps();
            $table->index('user_id');
            //$table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifications');
    }
}
