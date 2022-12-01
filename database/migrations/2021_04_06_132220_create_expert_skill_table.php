<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_skill', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expert_id')->unsigned();
            //$table->foreign('expert_id')->references('id')->on('experts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('skill_id')->unsigned();
            //$table->foreign('skill_id')->references('id')->on('skills')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('expert_skill');
    }
}
