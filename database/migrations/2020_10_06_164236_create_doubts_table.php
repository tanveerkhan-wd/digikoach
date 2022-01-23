<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoubtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doubts', function (Blueprint $table) {
            $table->increments('doubt_id');
            $table->integer('user_id');
            $table->integer('category_id')->nullable();
            $table->text('doubt_text')->nullable();
            $table->string('doubt_image', 100)->nullable();
            $table->string('doubt_attachment', 100)->nullable();
            $table->integer('doubt_upvote')->default(0);
            $table->integer('total_answers')->default(0);
            $table->tinyInteger('status')->comment("1=Approved,0=Rejected")->default(1);
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
        Schema::dropIfExists('doubts');
    }
}
