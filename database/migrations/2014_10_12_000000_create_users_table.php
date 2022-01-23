<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name',50)->nullable();
            $table->string('email',100)->nullable();
            $table->string('info_email',100)->nullable();
            $table->string('password',255)->nullable();
            $table->string('mobile_number',10)->nullable();
            $table->string('user_photo',255)->nullable();
            $table->string('user_reset_token',255)->nullable();
            $table->integer('user_fav_category')->nullable();
            $table->tinyInteger('user_type')->comment("0=Admin, 1=SubAdmin, 2=Student");
            $table->string('user_lang_code',2)->comment("en=English, hi=Hindi")->default('en');
            $table->tinyInteger('user_status')->comment("1=Active,0=Inactive");
            $table->tinyInteger('is_setup_completed')->comment("0=Not Completed, 1=Completed")->default(0)->nullable();
            $table->tinyInteger('is_mobile_verify')->comment("0=Not Verify, 1=Verify")->default(0);
            $table->tinyInteger('is_email_verify')->comment("0=Not Verify, 1=Verify")->default(0);
            $table->string('device_token',255)->nullable();
            $table->dateTime('last_logged_in')->nullable();
            $table->tinyInteger('deleted')->comment("0=Not Deleted, 1=Deleted")->default(0);
            $table->boolean('deactivated')->default(0);
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
