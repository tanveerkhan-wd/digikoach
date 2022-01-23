<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_descs', function (Blueprint $table) {
            $table->increments('banner_desc_id');
            $table->integer('banner_id')->unsigned()->nullable();
            $table->string('lang_code',2)->nullable();
            $table->string('banner_file',255)->nullable();
            $table->timestamps();
            $table->foreign('banner_id')->references('banner_id')->on('banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_descs');
    }
}
