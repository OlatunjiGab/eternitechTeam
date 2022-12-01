<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdIntoFrontendClientActivitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontend_client_activitys', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->nullable()->default(null);
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
            $table->dropColumn('company_id');
        });
    }
}
