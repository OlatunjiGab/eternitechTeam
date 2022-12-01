<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsTrainedIsManualProjectsTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects_translated', function (Blueprint $table) {
            $table->tinyInteger('is_trained')->default(0)->after('client_company_size');
            $table->tinyInteger('is_manual')->default(0)->after('is_trained');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects_translated', function (Blueprint $table) {
            $table->dropColumn('is_trained');
            $table->dropColumn('is_manual');
        });
    }
}
