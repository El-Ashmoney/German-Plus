<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('german');
            $table->string('arabic')->nullable();
            $table->string('english')->nullable();
            $table->string('note')->nullable();
            $table->string('image')->nullable();
            $table->string('sound')->nullable();
            $table->boolean("is_valid")->default(0);
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
        Schema::dropIfExists('words');
    }
}
