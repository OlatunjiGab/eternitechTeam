<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSkill extends Model
{
	
    protected $guarded = [];
    /**
     * save skills on the project
     * @param $projectId integer mandatory
     * @param $aSkill array mandatory
     */
    public function saveProjectSkills(int $projectId,array $aSkill){
    	if(!empty($aSkill) && !empty($projectId)){
	    	$projectSkillData = ['project_id'=>$projectId];
			$aProjectSkill = array_map(function($val) use($projectSkillData){return array_merge(['skill_id'=>$val],$projectSkillData);},$aSkill);
			
			$this->insert($aProjectSkill);
    	}
    
    }
}
