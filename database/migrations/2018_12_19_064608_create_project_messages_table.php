<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            //$table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->string('channel',50);
            $table->text('channel_info');
            $table->text('message');
            $table->integer('sender_id')->unsigned();
            //$table->foreign('sender_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('project_messages');
    }
}
