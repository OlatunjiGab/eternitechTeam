<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAutomateMessageLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('automate_message_log')) {
            Schema::drop('automate_message_log');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('automate_message_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->string('email');
            $table->tinyInteger('is_deleted')->default(0)->comment('0: not-deleted; 1:deleted');
            $table->tinyInteger('is_sent')->default(0)->comment('0: not-sent; 1:sent');
            $table->timestamps();
        });
    }
}
