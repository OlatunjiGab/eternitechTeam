<?php
/**
* Model genrated using LaraAdmin
* Help: http://laraadmin.com
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use SoftDeletes;
	
	protected $table = 'skills';
	
	protected $hidden = [
        'pivot',
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

    const CACHE_KEY = 'cache_all_skills';

    const KEYWORD_SEPARATOR = ',';

    public function experts()
    {
        return $this->belongsToMany('App\Models\Expert')->withTimestamps();
    }
    public function Portfolios()
    {
        return $this->belongsToMany('App\Models\Portfolio', 'portfolio_skills', 'skill_id', 'portfolio_id');
    }

    public static function getAllSkills() {
        return \Cache::get(self::CACHE_KEY, function() {
        	return self::where('deleted_at',null)->get();
        });
    }

	public static function detectSkills($content,$type = 'all',$isCommaString=false):array{

		$aProjectSkill = $aProjectSkillLocal = [];
	    if(!empty($content)){
	    	if ($isCommaString) {
	    		$content = array_map("trim",explode(',',strtolower($content)));
	    	} else {
	    		$content = array_map("trim",explode(' ',strtolower($content)));
	    	}
	        $aSkills = self::whereNULL('deleted_at')->get();
	        if(!empty($aSkills)){
	        	foreach ($aSkills as $key => $skill){
	        		if (!empty($skill->keywords)){
                        $keywords = $skill->getKeywordsArray();

		        		foreach ($keywords as $k => $keyword){
		        			if(in_array($keyword,$content) && !in_array($skill->id,$aProjectSkillLocal)){
		        				$aProjectSkill[] = $skill;
		        				$aProjectSkillLocal[] = $skill->id;
		        			}
		        		}
	        		}
	        	}
	        }
    	}
    	$response = [];
    	switch ($type) {
    		case 'list':
    			$response = $aProjectSkillLocal;
    			break;
    		
    		default:
    			$response = $aProjectSkill;
    			break;
    	}

    	return $response;
	}

	public function getKeywordsArray($isLowerCase = true) {
        $strIsJson = is_string($this->keywords) && is_array(json_decode($this->keywords, true)) ? true : false ;
        if ($strIsJson) {
            $aKeywords = json_decode(strtolower($this->keywords), true);
            $keywords = array_filter(array_map("trim", (array)$aKeywords));
        } else {
            $keywords = array_filter(array_map("trim",explode(self::KEYWORD_SEPARATOR, strtolower($this->keywords))));
        }

        // add main skill keyword
        $keywords[] = trim($this->keyword);

        if ($isLowerCase) {
            $keywords = array_map('strtolower', $keywords);
        }

        return array_unique($keywords);
    }

    public static function getSkillList() {
        return self::select('id', 'keywords','keyword')->get()->toArray();
    }
}