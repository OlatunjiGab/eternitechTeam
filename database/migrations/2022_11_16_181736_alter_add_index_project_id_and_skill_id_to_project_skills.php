<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddIndexProjectIdAndSkillIdToProjectSkills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_skills', function (Blueprint $table) {
            $table->index('project_id');
            $table->index('skill_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_skills', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['skill_id']);
        });
    }
}
