<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->increments('email_log_id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('subject',255)->nullable();
            $table->longText('email_content')->nullable();
            $table->tinyInteger('email_status')->comment("1=Send,2=Pending,3=NotSend");
            $table->longText('email_error_message')->nullable();
            $table->integer('email_attempt')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
