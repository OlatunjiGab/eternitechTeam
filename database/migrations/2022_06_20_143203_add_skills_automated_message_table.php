<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkillsAutomatedMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automated_message', function (Blueprint $table) {
            $table->string('skills', 255)->nullable()->after('period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('automated_message', function (Blueprint $table) {
            $table->dropColumn('skills');
        });
    }
}
