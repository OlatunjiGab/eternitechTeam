<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\Skill;
use App\Models\Expert;
use Illuminate\Http\Request;

class TalentsController extends Controller
{
    /**
     * get data on talent hire developer page for filter dropdown
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilters(Request $request)
    {
        $countries = Country::getAllCountries();
        $skills = Skill::getAllSkills();
        $experts = Expert::getItems($request->all());
        return response()
            ->json(array(
                'countries' => $countries,
                'skills'    => $skills->toArray(),
                'experts'   => $experts
            ));
    }

    /**
     * get expert list according to skill
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExpertDetails(Request $request, $id){
        $expert = Expert::where('experts.id',$id)->with(['country_data'=>function($qry){$qry->addSelect(['id','name','iso','flag']);},
                                'skills'=>function($qry){$qry->addSelect(['skills.id','skills.keyword','skills.url','skills.icon']);}])
                            ->select(['experts.*',\DB::raw('experts.skills as skill_ids')])
                            ->first();
        $skills = json_decode($expert->skill_ids,true);
        $similarExperts = Expert::getSimilarExperts($skills,$expert->id);
        if($similarExperts->count()==0){
            $similarExperts = Expert::getSimilarExperts([],$expert->id);
        }
        return response()
            ->json(array(
                'expert'            => $expert,
                'similarExperts'    => $similarExperts
            ));
    }
}
