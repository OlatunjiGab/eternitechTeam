<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class InterestedPartner extends Model
{
    protected $table = 'interested_partners';

    public function user() {
//        $return = $this->hasOne(User::class, 'id', 'user_id');
        $return = $this->hasOne(Employee::class, 'id', 'user_id');
        return $return;
    }
}
