<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierSkill extends Model
{
    protected $guarded = [];


    public function supplier(){
		return $this->belongsTo('App\Models\Supplier');
	}
	
    public function getSupplierCompanyUserBySkills($aSkill,$fetchType = 'first'){
		//return $this->whereIn('supplier_skills.skill_id',$aSkill)->with(['supplier.company.users'])->toSql();
		return $this->join('suppliers','suppliers.id','=','supplier_skills.supplier_id')
					->join('users','users.company_id','=','suppliers.company_id')
					->select('users.*')
					->whereIn('supplier_skills.skill_id',$aSkill)
					->groupBy('users.id')
					->orderBy('users.company_id','DESC')
					->first();
	}
}
