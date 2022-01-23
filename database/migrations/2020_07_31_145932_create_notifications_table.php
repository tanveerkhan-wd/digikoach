<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('notification_id');
            $table->integer('user_id');
            $table->string('notification_type',15)->nullable()->comment('CHALLENGE, RESULT, etc');
            $table->integer('ntoification_type_id')->nullable();
            $table->text('notification_data')->nullable();
            $table->tinyInteger('status')->comment("1=read,0=unread")->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
