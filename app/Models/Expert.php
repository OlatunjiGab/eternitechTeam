<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expert extends Model
{
    use SoftDeletes;
	
	protected $table = 'experts';

	static protected $record = '20';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

    protected $appends = ['image_url'];

	/*protected $casts =  [
	    'skills' => 'array'
	];*/

	const CACHE_KEY = 'cache_all_experts';

	public function country_data(){
		return $this->belongsTo('App\Models\Country','country');
	}

	public function skills(){
        return $this->belongsToMany('App\Models\Skill')->withTimestamps();
    }
    public function partner(){
        return $this->belongsTo('App\Models\Supplier','partner');
    }

    public function upload() {
        return $this->hasOne(Upload::class,'id','profile_image');
    }

    /**
     * get the image_url
     *
     */
    protected function getImageUrlAttribute()
    {
        return $this->upload?$this->upload->path():null;
    }

    public static function saveSkills($id,$skills=[]){
    	if(!empty($skills)){
	    	$expert = self::where('id',$id)->first();
	    	if(!empty($expert)){
		    	$expert->skills()->detach();
				$expert->skills()->attach($skills);
	    	}
    	}
    } 

	public static function getItems($request) {
		$condition = [];
		$expertList = self::with(['country_data'=>function($qry){$qry->addSelect(['id','name','iso','flag']);},
								'skills'=>function($qry){$qry->addSelect(['skills.id','skills.keyword','skills.url','skills.icon']);}]);
        $expertList = $expertList->where('is_live','=','1')->where('publish_type','=','Full details')->where('experts.description','!=','');
		if(!empty($request)){
			if(isset($request['keyword']) && !empty($request['keyword'])){
				$expertList = $expertList->where(function ($query) use($request) {
								    $query->whereRaw('CONCAT(experts.first_name," ",experts.last_name) LIKE ?', [$request['keyword']]);
								});
			}
			if(isset($request['skill']) && !empty($request['skill'])){
				$expertList = $expertList->join('expert_skill',function($join){
												$join->on('expert_skill.expert_id', 'experts.id');
											})
										->join('skills',function($join){
												$join->on('skills.id', 'expert_skill.skill_id');
											})
								->where('skills.keyword','LIKE',$request['skill']);
			}
			if(isset($request['country']) && !empty($request['country'])){
				$expertList = $expertList->join('countries',function($join){
												$join->on('countries.id', 'LIKE', 'experts.country');
											})->where('countries.name','LIKE','%'.$request['country'].'%');
			}
		}
        return $expertList->select(['experts.*'])->groupBy('experts.id')->paginate(self::$record);
    }

    public static function getSimilarExperts($skills,$id){
    	$expertList = self::with(['country_data'=>function($qry){$qry->addSelect(['id','name','iso','flag']);},
								'skills'=>function($qry){$qry->addSelect(['skills.id','skills.keyword','skills.url','skills.icon']);}]);
        $expertList = $expertList->where('is_live','=','1')->where('publish_type','=','Full details')->where('experts.description','!=','');
    	if(!empty($skills)){
	    	$expertList = $expertList->join('expert_skill',function($join){
													$join->on('expert_skill.expert_id', 'experts.id');
												})
									->whereIn('expert_skill.skill_id',$skills);
    	}
		$expertList = $expertList->whereNotIn('experts.id',[$id]);
		
		return $expertList->select(['experts.*'])->groupBy('experts.id')->limit(2)->get();
    }
}
