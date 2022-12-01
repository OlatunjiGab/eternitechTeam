<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE projects t1 INNER JOIN projects t2 ON t1.id = t2.id SET t1.project_type = 2 WHERE t2.partner_id != 0");
        DB::statement("UPDATE projects t1 INNER JOIN projects t2 ON t1.id = t2.id SET t1.project_type = 1 WHERE t2.automated = 1");
        DB::statement("UPDATE projects t1 INNER JOIN projects t2 ON t1.id = t2.id SET t1.project_type = 0 WHERE t2.automated = 0 AND t2.partner_id = 0 AND t2.project_type IS NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
