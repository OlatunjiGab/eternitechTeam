<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemoteIsClientItCompanyEnumValueProjectsTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE projects_translated MODIFY remote ENUM('Yes', 'No', 'Not Available')");
        DB::statement("ALTER TABLE projects_translated MODIFY is_client_it_company ENUM('Yes', 'No', 'Not Available')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE projects_translated MODIFY remote ENUM('Yes', 'No')");
        DB::statement("ALTER TABLE projects_translated MODIFY is_client_it_company ENUM('Yes', 'No')");
    }
}
