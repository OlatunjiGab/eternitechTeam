<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePortfolioSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolio_skills', function (Blueprint $table) {
            $table->dropForeign('portfolio_skills_portfolio_id_foreign');
            $table->foreign('portfolio_id')
                ->references('id')->on('portfolios')
                ->onDelete('cascade')->change();
            $table->dropForeign('portfolio_skills_skill_id_foreign');
            $table->foreign('skill_id')
                ->references('id')->on('skills')
                ->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portfolio_skills', function (Blueprint $table) {
            //
        });
    }
}
