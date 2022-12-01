<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryCodeColumnInProjectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->string('country_code','10')->default(null)->nullable();
        });
        Schema::table('frontend_client_activitys', function (Blueprint $table) {
            $table->string('country_code','10')->default(null)->nullable();
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
            $table->dropColumn('country_code');
        });
        Schema::table('frontend_client_activitys', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
}
