<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTemplateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('template_messages', function (Blueprint $table) {
            $table->tinyInteger('time')->default(0)->after('status');
            $table->tinyInteger('period')->default(0)->comment('1: hours, 2: days, 3:months, 4:years')->after('time');
            $table->tinyInteger('type')->default(0)->comment('0: default, 1: Automated')->after('period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_messages', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->dropColumn('period');
            $table->dropColumn('type');
        });
    }
}
