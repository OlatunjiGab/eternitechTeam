<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendClientActivities extends Model
{
    public $timestamps = true;
	protected $table = 'frontend_client_activitys';

	protected $fillable = ['updated_at', 'project_id', 'type', 'url', 'additional_information', 'ip_address', 'browser_name', 'os_name', 'city', 'country', 'browser_version', 'os_version', 'device_type', 'created_at','company_id'];
}
