<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSystemCreatedToProjectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->tinyInteger('is_system_created')->default(0)->after('sender_id');
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
            $table->dropColumn('automated');
        });
    }
}
