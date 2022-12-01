<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug',100)->unique();
            $table->string('title');
            $table->text('content');
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
        Schema::drop('template_messages');
    }
}
