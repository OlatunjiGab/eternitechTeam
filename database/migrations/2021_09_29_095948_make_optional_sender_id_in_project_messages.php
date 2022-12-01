<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeOptionalSenderIdInProjectMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->integer('sender_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->integer('sender_id')->unsigned()->nullable(false)->change();
        });
    }
}
