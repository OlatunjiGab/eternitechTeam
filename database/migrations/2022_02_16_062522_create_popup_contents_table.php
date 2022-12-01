<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popup_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->default(null)->nullable();
            $table->text('content')->default(null)->nullable();
            $table->string('background_image',255)->default(null)->nullable();
            $table->string('specific_page')->default(null)->nullable();
            $table->string('pop_after')->default(20)->nullable();
            $table->string('country',255)->default(null)->nullable();
            $table->string('source',255)->default(null)->nullable();
            $table->string('status',255)->default(null)->nullable();
            $table->integer('company_id')->default(null)->nullable();
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
        Schema::dropIfExists('popup_contents');
    }
}
