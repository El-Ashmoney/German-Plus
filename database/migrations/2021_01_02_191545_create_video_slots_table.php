<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('video_id')->nullable()->unsigned();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('featured_sentence')->nullable();
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
        Schema::dropIfExists('video_slots');
    }
}
