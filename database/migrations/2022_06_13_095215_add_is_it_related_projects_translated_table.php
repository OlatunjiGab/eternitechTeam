<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsItRelatedProjectsTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects_translated', function (Blueprint $table) {
            $table->tinyInteger('is_it_related')->default(1)->after('is_manual');
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
            $table->dropColumn('is_it_related');
        });
    }
}
