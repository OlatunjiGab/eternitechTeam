<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddIpBrowserOsColumnInFrontendClientActivitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('frontend_client_activitys', function (Blueprint $table) {
            $table->string('ip_address','255')->default(null)->nullable();
            $table->string('browser_name','255')->default(null)->nullable();
            $table->string('os_name','255')->default(null)->nullable();
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
            $table->dropColumn('ip_address');
            $table->dropColumn('browser_name');
            $table->dropColumn('os_name');
        });

    }
}
