<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',256)->default(null)->nullable();
            $table->string('slug',256)->default(null)->nullable();
            $table->string('image',256)->default(null)->nullable();
            $table->string('client_name',256)->default(null)->nullable();
            $table->longText('description')->nullable();
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->string('skills',256)->default(null)->nullable();
            $table->string('url',256)->default(null)->nullable();
            $table->string('video_embed_code',256)->default(null)->nullable();
            $table->string('score',10)->default(1)->nullable();
            $table->tinyInteger('is_live',false,true)->default(0)->nullable();
            $table->tinyInteger('done_by_eternitech',false,true)->default(0)->nullable();
            $table->integer('partner_id',false,true)->default(null)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}
