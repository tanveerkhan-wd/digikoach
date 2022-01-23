<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms', function (Blueprint $table) {
            $table->increments('cms_id');
            $table->string('slug',100)->nullable();
            $table->string('seo_meta_title',255)->nullable();
            $table->string('seo_meta_description',255)->nullable();
            $table->tinyInteger('status')->comment("1=Active,0=Inactive")->default(1);
            $table->integer('created_by')->comment("User Id")->nullable();
            $table->integer('updated_by')->comment("User Id")->nullable();
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
        Schema::dropIfExists('cms');
    }
}
