<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectClientActivity extends Model
{
    public $timestamps = true;
	protected $table = 'project_client_activitys';

	protected $fillable = ['updated_at', 'user_id', 'project_id', 'type', 'url', 'additional_information', 'created_at'];
}
