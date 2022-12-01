<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpertIdColumnInProjectCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_companies', function (Blueprint $table) {
            $table->integer('expert_id')->unsigned()->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_companies', function (Blueprint $table) {
            $table->integer('expert_id')->unsigned()->nullable()->default(null);
        });
    }
}
