<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsAutomatedMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automated_message', function (Blueprint $table) {
            $table->renameColumn('event_type', 'trigger_event_type');
            $table->string('trigger_event_config', 255)->nullable();

            $table->renameColumn('skills', 'lead_filter_skills');
            $table->renameColumn('project_type', 'lead_filter_source');
            $table->renameColumn('channel', 'lead_filter_channel');
            $table->string('lead_filter_countries', 255)->nullable();

            $table->renameColumn('template_id', 'action_template_id');
            $table->string('action_message_channel', 255)->nullable();
            $table->renameColumn('time', 'action_delay');
            $table->renameColumn('period', 'action_delay_unit');
            $table->renameColumn('sender_email', 'action_sender_email');
            $table->renameColumn('sender_name', 'action_sender_name');

            $table->string('name', 255)->after('id');
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
            $table->renameColumn('trigger_event_type', 'event_type');
            $table->dropColumn('trigger_event_config');

            $table->renameColumn('lead_filter_skills', 'skills');
            $table->renameColumn('lead_filter_source', 'project_type');
            $table->renameColumn('lead_filter_channel', 'channel');
            $table->dropColumn('lead_filter_countries');

            $table->renameColumn('action_template_id', 'template_id');
            $table->dropColumn('action_message_channel');
            $table->renameColumn('action_delay', 'time');
            $table->renameColumn('action_delay_unit', 'period');
            $table->renameColumn('action_sender_email', 'sender_email');
            $table->renameColumn('action_sender_name', 'sender_name');

            $table->dropColumn('name');
        });
    }
}
