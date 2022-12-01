<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUseragentFieldToFrontendClientActivitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontend_client_activitys', function (Blueprint $table) {
            $table->string('city','255')->default(null)->nullable();
            $table->string('country','255')->default(null)->nullable();
            $table->string('browser_version','255')->default(null)->nullable();
            $table->string('os_version','255')->default(null)->nullable();
            $table->string('device_type','255')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frontend_client_activitys', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('browser_version');
            $table->dropColumn('os_version');
            $table->dropColumn('device_type');
        });
    }
}
