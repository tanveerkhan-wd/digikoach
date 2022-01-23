<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialDescsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonial_descs', function (Blueprint $table) {
            $table->increments('testimonial_desc_id');
            $table->integer('testimonial_id')->unsigned()->nullable();
            $table->string('lang_code',2)->nullable();
            $table->string('testimonial_name',255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('testimonial_id')->references('testimonial_id')->on('testimonials')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testimonial_descs');
    }
}
