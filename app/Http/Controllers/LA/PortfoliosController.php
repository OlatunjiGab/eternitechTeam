<?php

namespace App\Http\Controllers\LA;

use App\Classes\Slack;
use App\Helpers\Message;
use App\Models\Portfolio;
use App\Models\Skill;
use App\Models\Upload;
use App\Notifications\ExceptionCought;
use Collective\Html\FormFacade as Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Entrust;
use Image;
use Validator;
use Datatables;
use LAConfigs;

class PortfoliosController extends Controller
{
    public $listing_cols = ['id', 'title', 'slug','client_name', 'is_live', 'created_at'];
    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('la.portfolios.index', [
            'user' => $user
        ]);
    }

    public function create() {
        $skillList  = Skill::getSkillList();
        $scoreList  = Portfolio::SCORE_LIST;
        $isLiveList = Portfolio::IS_LIVE_LIST;
        $doneByList = Portfolio::DONE_BY_ETERNITECH_LIST;
        $isNDA      = Portfolio::IS_NDA_LIST;
        return view('la.portfolios.create',['scoreList' => $scoreList, 'isLiveList' => $isLiveList, 'skillList' => $skillList, 'doneByList' => $doneByList, 'isNDA' => $isNDA]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data,
            [
                'title'       => 'required|unique:portfolios',
                'client_name' => 'required',
                'description' => 'required',
                /*'problem'     => 'required',
                'solution'    => 'required',*/
                'skills'      => 'required|array',
                'url'         => 'active_url',
                'image_file' => 'image|mimes:jpeg,png,jpg|max:1000',
                'banner_file' => 'image|mimes:jpeg,png,jpg|max:1000',
            ],
            [
                'description.required' => 'The project description field is required.',
            ]
        );
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $uploaddocid = null;
            if($request->hasfile('image_file')) {
                $file = $request->file('image_file');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
                $img = Image::make($file->path());
                $img->resize(249, 249, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($folder.DIRECTORY_SEPARATOR.$date_append.$fname);

                //$file->move($folder, $date_append . $fname);
                $path = $folder . DIRECTORY_SEPARATOR . $date_append . $fname;
                if (!empty($fname)) {
                    $uploadCV = Upload::create([
                        'name' => $fname,
                        'path' => $path,
                        'extension' => pathinfo($fname, PATHINFO_EXTENSION),
                        "caption" => "",
                        "hash" => "",
                        "public" => true,
                        'user_id' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    // apply unique random hash to file
                    while (true) {
                        $hash = strtolower(str_random(20));
                        if (!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
                    $uploaddocid = $uploadCV->id;
                }
            }
            $uploaddocid ? $data['image'] = $uploaddocid : null;
            $data['slug'] = $data['title'];

            $bannerUploaddocid = null;
            if($request->hasfile('banner_file')) {
                $file = $request->file('banner_file');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
                $img = Image::make($file->path());
                $file->move($folder, $date_append . $fname);
                $path = $folder . DIRECTORY_SEPARATOR . $date_append . $fname;
                if (!empty($fname)) {
                    $uploadCV = Upload::create([
                        'name' => $fname,
                        'path' => $path,
                        'extension' => pathinfo($fname, PATHINFO_EXTENSION),
                        "caption" => "",
                        "hash" => "",
                        "public" => true,
                        'user_id' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    // apply unique random hash to file
                    while (true) {
                        $hash = strtolower(str_random(20));
                        if (!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
                    $bannerUploaddocid = $uploadCV->id;
                }
            }
            $bannerUploaddocid ? $data['banner'] = $bannerUploaddocid : null;
            $portfolio = Portfolio::create($data);
            $portfolio->skillS()->attach($data['skills']);

            $successMessage = "Portfolio item submitted successfully to admin for approval";
            if(Entrust::hasRole('SUPER_ADMIN')) {
                $successMessage = "Portfolio has been saved successfully.";
            }
            return redirect()->route(config('laraadmin.adminRoute') . '.portfolios.index')->with('success', $successMessage);
        }
    }

    public function edit($id)
    {
        $skillList  = Skill::getSkillList();
        $scoreList  = Portfolio::SCORE_LIST;
        $isLiveList = Portfolio::IS_LIVE_LIST;
        $doneByList = Portfolio::DONE_BY_ETERNITECH_LIST;
        $isNDA      = Portfolio::IS_NDA_LIST;
        $portfolio = Portfolio::with('skills')->findOrFail($id);
        $selectedSkills = $portfolio->skills()->select('skill_id')->pluck('skill_id','skill_id')->toArray();
        $user = Auth::user();
    
    return view('la.portfolios.edit',['skillList' => $skillList, 'scoreList' => $scoreList, 'isLiveList' => $isLiveList, 'doneByList' => $doneByList, 'portfolio' => $portfolio, 'selectedSkills' => $selectedSkills, 'user' => $user, 'isNDA' => $isNDA]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data,
            [
                'title'       => 'required|unique:portfolios,title,'.$id,
                'client_name' => 'required',
                'description' => 'required',
                /*'problem'     => 'required',
                'solution'    => 'required',*/
                'skills'      => 'required|array',
                'url'         => 'active_url',
                'image_file' => 'image|mimes:jpeg,png,jpg|max:1000',
                'banner_file' => 'image|mimes:jpeg,png,jpg|max:1000',
            ],
            [
                'description.required' => 'The project description field is required.',
            ]
        );

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $uploaddocid = null;
            if($request->hasfile('image_file')) {
                $file = $request->file('image_file');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
                $img = Image::make($file->path());
                $img->resize(249, 249, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($folder.DIRECTORY_SEPARATOR.$date_append.$fname);
                //$file->move($folder, $date_append . $fname);
                $path = $folder . DIRECTORY_SEPARATOR . $date_append . $fname;
                if (!empty($fname)) {
                    $uploadCV = Upload::create([
                        'name' => $fname,
                        'path' => $path,
                        'extension' => pathinfo($fname, PATHINFO_EXTENSION),
                        "caption" => "",
                        "hash" => "",
                        "public" => true,
                        'user_id' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    while (true) {
                        $hash = strtolower(str_random(20));
                        if (!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
                    $uploaddocid = $uploadCV->id;
                }
            }
            $uploaddocid ? $data['image'] = $uploaddocid : null;

            $bannerUploaddocid = null;
            if($request->hasfile('banner_file')) {
                $file = $request->file('banner_file');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
                $img = Image::make($file->path());
                $file->move($folder, $date_append . $fname);
                $path = $folder . DIRECTORY_SEPARATOR . $date_append . $fname;
                if (!empty($fname)) {
                    $uploadCV = Upload::create([
                        'name' => $fname,
                        'path' => $path,
                        'extension' => pathinfo($fname, PATHINFO_EXTENSION),
                        "caption" => "",
                        "hash" => "",
                        "public" => true,
                        'user_id' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    while (true) {
                        $hash = strtolower(str_random(20));
                        if (!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
                    $bannerUploaddocid = $uploadCV->id;
                }
            }
            $bannerUploaddocid ? $data['banner'] = $bannerUploaddocid : null;
            $data['slug'] = $data['title'];
            $portfolio = Portfolio::where('id','=',$id)->first();
            $portfolio->update($data);
            $portfolio->skillS()->sync($data['skills']);
            return redirect()->route(config('laraadmin.adminRoute') . '.portfolios.index')->with('success',
                'Portfolio has been Updated successfully.');
        }
        return back()->with("flash.success","Portfolio has been Updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Portfolio::destroy($id);
            return back()->with('flash.success','Portfolio has been deleted successfully');
        }catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
            return back()->with('flash.error',$e->getMessage());
        }
    }

    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtAjax(Request $request)
    {
        if(Entrust::hasRole('SUPER_ADMIN')){
            $values = Portfolio::select($this->listing_cols)->orderBy('portfolios.id','ASC');
        } else {
            $columns = array_flip($this->listing_cols);
            unset($columns['is_live']);
            $columns = array_flip($columns);
            $values = Portfolio::select($columns)->where('partner_id','=',Auth::user()->supplier_id)->orderBy('portfolios.id','ASC');
        }

        $out = Datatables::of($values)->make();
        $data = $out->getData();
        foreach($data->data as $k=>$val) {
            $title = $val[1];
            $slug = $val[2];
            $clientName = $val[3];
            if(Entrust::hasRole('SUPER_ADMIN')){
            $isLive = $val[4];
            $className = ($isLive == "Yes") ?'btn-success':'btn-primary';
            $button = Form::open([
                    'url'  => [config('laraadmin.adminRoute') . '/portfolio-is-live', $val[0]],
                    'method' => 'post',
                    'style'  => 'display:inline',
                ]);
            $button .= "<button class='btn $className btn-xs' type='submit'>$isLive</button>";
            $button .= Form::close();
            $addedAt = $val[5];
            $data->data[$k] = [$val[0],$title,$slug,$clientName,$button,$addedAt];
            } else {
                $addedAt = $val[4];
                $data->data[$k] = [$val[0],$title,$slug,$clientName,$addedAt];
            }

            $output = '';
            $output .= '<a href="'.route(config('laraadmin.adminRoute') . '.portfolios.edit',$val[0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
            $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.portfolios.destroy', $val[0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
            $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
            $output .= Form::close();
            $data->data[$k][] = (string)$output;
        }
        $out->setData($data);
        return $out;
    }

    public function getPortfolios(Request $request) {

        $portfolios = Portfolio::getPortfolios($request);
        return response()->json(
            [
                'status'     => true,
                'portfolios' => $portfolios,
            ]
        );

    }
    public function getPortfolioDetail(Request $request,$slug) {

        $portfolio = Portfolio::with(['skills' => function ($query) {
            $query->select('skills.id', 'skills.keyword');}
        ])->where('slug','=',$slug)->first();
        if($portfolio){
            $similarPortfolios = (object) [];
            $skillIds = [];

            if(count($portfolio->skills)) {
                $skills = $portfolio->skills;
                $skillIds = $skills->pluck('id')->toArray();
            }

            if(count($skillIds)) {
                $similarPortfolios = Portfolio::with(['skills' => function ($query) { $query->select('skills.id', 'skills.keyword');}])
                ->whereHas('skills', function ($query) use ($skillIds){ $query->whereIn('skills.id',$skillIds); })
                ->where('id','!=',$portfolio->id)
                ->limit(6)
                ->get()->toArray();
            }
            $portfolio = $portfolio->toArray();
            unset($portfolio['upload']);

            $data['status'] = true;
            $data['portfolio'] = $portfolio;
            $data['similarPortfolios'] = $similarPortfolios;
        } else {
            $data['status'] = true;
            $data['portfolio'] = (object) [];
            $data['similarPortfolios'] = (object) [];
        }
        return response()->json($data);
    }
    public function isLive(Request $request,$id) {

        $portfolio = Portfolio::with('partner')->find($id);
        if($portfolio->is_live == Portfolio::IS_LIVE_LIST[Portfolio::IS_YES]) {
            $portfolio->is_live = Portfolio::IS_NO;
        } else {
            $portfolio->is_live = Portfolio::IS_YES;
            if($portfolio->partner_id && $portfolio->partner->partner_email){
                $params = [
                    'partnerName' => $portfolio->partner->partner_first_name,
                    'portfolioTitle' => $portfolio->title,
                    'createDate' => $portfolio->created_at->todatestring(),
                ];
                $config = array(
                    'template' => 'portfolio_approve',
                    'subject' => "Portfolio Item Approved",
                    'params' => $params,
                    'from' => [LAConfigs::getByKey('default_email')],
                    'to' => [$portfolio->partner->partner_email]
                );
                Message::sendSystemEmail($config);
            }
        }
        $portfolio->save();

        return redirect()->back()->with('success', "Portfolio has been Updated successfully.");

    }
}
