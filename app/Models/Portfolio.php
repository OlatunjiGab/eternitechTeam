<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    const IS_YES = 1;
    const IS_NO = 0;

    const IS_LIVE_LIST = [
        self::IS_YES => "Yes",
        self::IS_NO => "No",
    ];

    const DONE_BY_ETERNITECH_LIST = [
        self::IS_YES => "Yes",
        self::IS_NO => "No",
    ];

    const IS_NDA_LIST = [
        self::IS_YES => "Yes",
        self::IS_NO => "No",
    ];

    const SCORE_LIST = [
        '1'=>'1',
        '2'=>'2',
        '3'=>'3',
        '4'=>'4',
        '5'=>'5',
        '6'=>'6',
        '7'=>'7',
        '8'=>'8',
        '9'=>'9',
        '10'=>'10',
    ];


    protected $table = 'portfolios';
    protected $with = ['upload','uploadBanner'];
    protected $appends = ['image_url','banner_url'];

    protected $fillable = ['title', 'slug', 'image', 'client_name', 'description', 'problem', 'solution', 'url', 'video_embed_code', 'score', 'is_live', 'done_by_eternitech', 'partner_id', 'is_nda', 'banner', 'created_at', 'updated_at'];

    /**
     * Set the slug
     *
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_slug($value);
    }

    /**
     * get the is_live
     *
     */
    public function getIsLiveAttribute($value)
    {
        return $this->attributes['is_live'] = self::IS_LIVE_LIST[$value];
    }

    /**
     * get the image_url
     *
     */
    protected function getImageUrlAttribute()
    {
        return $this->upload?$this->upload->path():null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function upload(){
        return $this->hasOne(Upload::class,'id','image');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function uploadBanner(){
        return $this->hasOne(Upload::class,'id','banner');
    }

    /**
     * get the banner_url
     *
     */
    protected function getBannerUrlAttribute()
    {
        return $this->uploadBanner?$this->uploadBanner->path():null;
    }

    public function partner(){
        return $this->hasOne(Supplier::class,'id','partner_id');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill', 'portfolio_skills', 'portfolio_id', 'skill_id');
    }

    public static function getPortfolios($request) {
        $portfolios = self::with(['skills' => function ($query) {
            $query->select('skills.id', 'skills.keyword');
        }]);
        $portfolios = $portfolios->where('portfolios.is_live','=',self::IS_YES);

        if($request->has('title')){
            $portfolios = $portfolios->where('title', 'like', "%".$request->title."%");
        }
        if($request->has('skill')){
            $portfolios->whereHas('skills', function ($qry) use($request) {
                $qry->where('keyword', 'like', "%".$request->skill."%");
            });
        }

        $portfolios = $portfolios->select(['portfolios.*']);

        $sortBy = "title";
        if($request->has('sortBy') && in_array($request->sortBy,['score','title'])) {
            $sortBy = $request->sortBy;
        }
        $sortOrder = "asc";
        if($request->has('sortOrder') && in_array($request->sortOrder,['asc','desc'])) {
            $sortOrder = $request->sortOrder;
        }
        $portfolios = $portfolios->orderBy($sortBy,$sortOrder);

        $perPage = 20;
        if($request->has('perPage') && is_numeric($request->perPage)) {
            $perPage = $request->perPage;
        }
        $portfolios = $portfolios->paginate($perPage)->appends($request->query());
        return $portfolios;
    }
}
