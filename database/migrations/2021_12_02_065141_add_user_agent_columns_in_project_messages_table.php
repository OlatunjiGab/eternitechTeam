<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAgentColumnsInProjectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->string('ip_address','255')->default(null)->nullable();
            $table->string('browser_name','255')->default(null)->nullable();
            $table->string('os_name','255')->default(null)->nullable();
            $table->string('city','255')->default(null)->nullable();
            $table->string('country','255')->default(null)->nullable();
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
            $table->dropColumn('ip_address');
            $table->dropColumn('browser_name');
            $table->dropColumn('os_name');
            $table->dropColumn('city');
            $table->dropColumn('country');
        });
    }
}
