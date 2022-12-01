<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomatedMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automated_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->default(0)->nullable();
            $table->tinyInteger('time')->default(0)->nullable();
            $table->tinyInteger('period')->default(0)->comment('1: hours, 2: days, 3:months, 4:years')->nullable();
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
        Schema::dropIfExists('automated_message');
    }
}
