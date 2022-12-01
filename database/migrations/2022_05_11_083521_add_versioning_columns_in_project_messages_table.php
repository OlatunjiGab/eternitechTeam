<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersioningColumnsInProjectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->integer('template_id')->nullable()->default(null);
            $table->string('version','10')->nullable()->default(null);
            $table->tinyInteger('user_engaged')->nullable()->default(0);
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
            $table->dropColumn('template_id');
            $table->dropColumn('version');
            $table->dropColumn('user_engaged');
        });
    }
}
