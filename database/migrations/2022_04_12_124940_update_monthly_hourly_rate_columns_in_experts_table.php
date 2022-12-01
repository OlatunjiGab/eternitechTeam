<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMonthlyHourlyRateColumnsInExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->date('monthly_rate')->nullable()->default(null)->change();
            $table->date('hourly_rate')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->date('monthly_rate')->nullable(false)->change();
            $table->date('hourly_rate')->nullable(false)->change();
        });
    }
}
