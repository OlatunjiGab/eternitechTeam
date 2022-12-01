<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
	
	protected $table = 'suppliers';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	public function supplierSkills(){
		return $this->hasMany('App\Models\SupplierSkill');
	}

	public function company(){
		return $this->belongsTo('App\Models\Company');
	}


	public static function getList(){
		return self::where('deleted_at',null)->select(['id',\DB::raw('TRIM(CONCAT(IFNULL(partner_first_name,"")," ",IFNULL(IF(partner_last_name="null","",partner_last_name),""))) as full_name')])->pluck('full_name', 'id')->toArray();
	}

    public function Portfolios(){
        return $this->hasMany(Portfolio::class,'partner_id','id');
    }

    public function Experts(){
        return $this->hasMany(Expert::class,'partner','id');
    }
}
