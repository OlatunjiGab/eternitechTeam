<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('automated_messages', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('title');
            $table->text('content');
            $table->string('time');
            $table->string('period');
            $table->string('language',20);
            $table->tinyInteger('status')->default(1)->comment('0: deactive; 1:active');
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
         Schema::drop('automated_messages');
    }
}
