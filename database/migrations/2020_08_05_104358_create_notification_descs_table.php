<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_descs', function (Blueprint $table) {
            $table->increments('notification_desc_id');
            $table->integer('notification_id')->unsigned()->nullable();
            $table->string('lang_code',2)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('notification_id')->references('notification_id')->on('notifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_descs');
    }
}
