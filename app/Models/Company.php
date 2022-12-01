<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Company extends Model
{
    use SoftDeletes;
	
	protected $table = 'companies';
	
	protected $hidden = [];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	const TYPE_AGENCY = 1;
	const TYPE_ENTREPRENEURS = 2;
	const TYPE_SALES = 3;
	const TYPE_CODER = 4;
	const TYPE_FREELANCER = 5;
	const TYPE_IT_FIRM = 6;
	const TYPE_PROJECT_MANAGER = 7;
	const TYPE_LEAD_GENERATOR = 8;

    const PLAN_COMMUNITY = 0;
    const PLAN_PREMIUM = 1;

    const TYPE_LIST = [
        self::TYPE_AGENCY => 'Agency',
        self::TYPE_ENTREPRENEURS => 'Entrepreneurs',
        self::TYPE_SALES => 'Sales',
        self::TYPE_CODER => 'Programmer',
        self::TYPE_FREELANCER => 'Freelancer',
        self::TYPE_IT_FIRM => 'IT Firm',
        self::TYPE_PROJECT_MANAGER => 'Project Manager',
        self::TYPE_LEAD_GENERATOR => 'Lead Generator',
    ];

    const PLAN_LIST = [
        self::PLAN_COMMUNITY => 'Community',
        self::PLAN_PREMIUM => 'Premium',
    ];

    const UNKNOWN_NAME = 'Unknown';
    const UNKNOWN_NAME_STRINGS = 'null|unknown';

	public function supplier(){
		return $this->hasOne('App\Models\Supplier');
	}

	public function users(){
		return $this->hasMany('App\User');
	}

    public function getName(){
        return $this->name ?? "No Name";
    }

    public function getLanguageName() {
        $languageName = $this->language;
        if($language = Language::select('name')->where('code','=',$languageName)->first())
        {
            $languageName =$language->name;
        }
        return $languageName;
    }

    public function getType() {
        $typeName = self::TYPE_LIST[$this->type];
        return $typeName;
    }

    public function getPlanType()
    {
        return self::PLAN_LIST[$this->plan_type];
    }

    public static function getCompanyList() {
        $list = self::select('id',DB::raw('CONCAT(name," ",email) AS fullCompanyName'))
            ->where('email', '!=', '')
            ->where('is_banned', '=', 0)
            ->get()
            ->pluck('fullCompanyName','id')
            ->toArray();
        return $list;
    }

    public static function isNameUnknown($name) {
	    if ($name == self::UNKNOWN_NAME) {
	        return true;
        }

        if (self::sanitizeName($name) == self::UNKNOWN_NAME) {
            return true;
        }

        return false;
    }

    public static function sanitizeName($name) {
	    if (empty($name)) {
	       return self::UNKNOWN_NAME;
        }

        if (preg_match('/(' . self::UNKNOWN_NAME_STRINGS . ')/i', $name,$matches)) {
            return self::UNKNOWN_NAME;
        }

        return trim($name);
    }
}
