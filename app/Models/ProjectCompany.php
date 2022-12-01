<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCompany extends Model
{
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
