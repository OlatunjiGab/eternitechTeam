<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_translated', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name','255')->nullable();
            $table->text('description')->nullable();
            $table->string('categories','100')->nullable();
            $table->string('project_budget','100')->nullable();
            $table->integer('is_hourly')->unsigned()->default(0)->nullable();
            $table->string('url','255')->nullable();
            $table->string('channel','255')->default('email')->nullable();
            $table->string('language','10')->default('en')->nullable();
            $table->tinyInteger('automated')->default(0)->nullable();
            $table->text('bid_desc')->nullable();
            $table->integer('status')->unsigned()->nullable();
            $table->timestamp('deleted_at')->nullable()->default(null);
            $table->timestamps();
            $table->integer('project_attention')->unsigned()->default(1)->nullable();
            $table->tinyInteger('affiliate')->default(0)->comment('0: default, 1: affiliate')->nullable();
            $table->integer('partner_id')->unsigned()->nullable()->default('0');
            $table->string('currency','10')->default(null)->nullable();
            $table->string('experience','50')->default(0)->nullable();
            $table->string('source','10')->default(null)->nullable();
            $table->date('follow_up_date')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects_translated');
    }
}
