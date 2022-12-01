<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('column_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key',256)->default(null)->nullable();
            $table->boolean('value')->default(0)->nullable();
            $table->timestamps();
        });
        $dateTime = date('Y-m-d H:i:s');
        $data = [
            ['key' => 'url', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'channel', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'project_budget', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'is_hourly', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'client_name', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'phone', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'email', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'project_attention', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'created_at', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'source', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'affiliate', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
            ['key' => 'skills', 'value' => '0', 'created_at'=>$dateTime, 'updated_at'=>$dateTime],
        ];
        DB::table('column_settings')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('column_settings');
    }
}
