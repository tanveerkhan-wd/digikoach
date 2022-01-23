<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_descs', function (Blueprint $table) {
            $table->increments('cms_desc_id');
            $table->integer('cms_id')->unsigned()->nullable();
            $table->string('lang_code',2)->nullable();
            $table->string('cms_title',255)->nullable();
            $table->text('cms_description')->nullable();
            $table->timestamps();
            $table->foreign('cms_id')->references('cms_id')->on('cms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_descs');
    }
}
