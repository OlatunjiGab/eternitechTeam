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
        DB::query("ALTER TABLE `portfolio_skills` DROP FOREIGN KEY `portfolio_skills_portfolio_id_foreign`; ALTER TABLE `portfolio_skills` ADD CONSTRAINT `portfolio_skills_portfolio_id_foreign` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `portfolio_skills` DROP FOREIGN KEY `portfolio_skills_skill_id_foreign`; ALTER TABLE `portfolio_skills` ADD CONSTRAINT `portfolio_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;");
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
