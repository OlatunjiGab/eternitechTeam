<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_skills', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('skill_id');
            //$table->foreign('skill_id')->references('id')->on('skills')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('supplier_id')->unsigned();
            $table->integer('experience')->default(0);
            $table->double('rate')->default(0);
            $table->longText('comment')->default(null);
            //$table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('supplier_skills');
    }
}
