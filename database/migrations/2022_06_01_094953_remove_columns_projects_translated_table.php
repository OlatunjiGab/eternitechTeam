<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsProjectsTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects_translated', function (Blueprint $table) {
            $table->dropColumn(['project_budget', 'is_hourly', 'url', 'language', 'automated', 'bid_desc', 'status', 'project_attention', 'affiliate', 'partner_id', 'currency', 'experience', 'source', 'follow_up_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
