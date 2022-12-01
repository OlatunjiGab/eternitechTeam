<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExpertColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->string('monthly_rate','100')->nullable()->default(null)->change();
            $table->string('hourly_rate','100')->nullable()->default(null)->change();
        });
        DB::statement("UPDATE experts SET monthly_rate = 0, hourly_rate= 0");
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
