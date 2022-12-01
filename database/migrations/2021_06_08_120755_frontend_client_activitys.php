<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FrontendClientActivitys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('frontend_client_activitys', function($table){
        $table->increments('id');
        $table->string('project_id');  
        $table->string('type');  
        $table->string('url');  
        $table->string('additional_information');    
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
       Schema::drop('frontend_client_activitys');
    }
}
