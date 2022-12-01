<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerAccess extends Model
{
    protected $table = 'partner_access';

    protected $fillable = ['route','is_access','user_id','created_at','updated_at'];

    public $timestamps = true;

    //
}
