<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsProjectsTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects_translated', function (Blueprint $table) {
            $table->string('skills', 255)->nullable()->after('channel');
            $table->string('service', 255)->nullable()->after('skills');
            $table->string('competition', 255)->nullable()->after('service');
            $table->string('project_type', 100)->nullable()->after('competition');
            $table->enum('remote',['Yes', 'No'])->default('No')->after('project_type');
            $table->string('provider_type', 100)->nullable()->after('remote');
            $table->string('provider_experience', 255)->nullable()->after('provider_type');
            $table->string('qualification', 100)->nullable()->after('provider_experience');
            $table->string('project_length', 100)->nullable()->after('qualification');
            $table->string('project_state', 255)->nullable()->after('project_length');
            $table->string('project_urgency', 100)->nullable()->after('project_state');
            $table->string('budget', 100)->nullable()->after('project_urgency');
            $table->string('client_knowlegeable', 100)->nullable()->after('budget');
            $table->string('client_experience_with_dev', 100)->nullable()->after('client_knowlegeable');
            $table->text('industry')->nullable()->after('client_experience_with_dev');
            $table->enum('is_client_it_company',['Yes', 'No'])->default('No')->after('industry');
            $table->string('client_company_size', 100)->nullable()->after('is_client_it_company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects_translated', function (Blueprint $table) {
            $table->dropColumn('skills');
            $table->dropColumn('service');
            $table->dropColumn('competition');
            $table->dropColumn('project_type');
            $table->dropColumn('remote');
            $table->dropColumn('provider_type');
            $table->dropColumn('provider_experience');
            $table->dropColumn('qualification');
            $table->dropColumn('project_length');
            $table->dropColumn('project_state');
            $table->dropColumn('project_urgency');
            $table->dropColumn('budget');
            $table->dropColumn('client_knowlegeable');
            $table->dropColumn('client_experience_with_dev');
            $table->dropColumn('industry');
            $table->dropColumn('is_client_it_company');
            $table->dropColumn('client_company_size');
        });
    }
}
