<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $casts = [
        'reserved_at' => 'dateTime',
        'available_at' => 'dateTime',
        'created_at' => 'dateTime',
    ];
}
