<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnChannelAndSenderEmailInAutomatedMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automated_message', function (Blueprint $table) {
            $table->string('channel')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_name')->nullable();
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
            $table->dropColumn('channel');
            $table->dropColumn('sender_email');
            $table->dropColumn('sender_name');
        });
    }
}
