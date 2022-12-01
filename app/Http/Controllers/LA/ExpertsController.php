<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Channels\BaseChannel;
use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\Language;
use App\Models\PartnerAccess;
use App\Models\Project;
use App\Models\ProjectCompany;
use DB;
use Illuminate\Http\Request;
use Auth;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use App\Models\Expert;
use App\Models\Upload;
use LAConfigs;
use Config;
use Symfony\Component\HttpFoundation\Response;
use Entrust;

 class ExpertsController extends Controller
{
	public $show_action = true;
	public $view_col = 'url_slug';
	public $listing_cols = ['id', 'first_name', 'last_name', 'country', 'email', 'phone', 'partner', 'skills', 'headline', 'description', 'experience_start', 'experience', 'monthly_rate', 'hourly_rate', 'publish_type'];
	
	public function __construct() {
        parent::__construct();
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Experts', $this->listing_cols);
				return $next($request);
			});
		} else {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ModuleFields::listingColumnAccessScan('Experts', $this->listing_cols);
                return $next($request);
            });
		}
	}
	
	/**
	 * Display a listing of the Experts.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        $user = Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Experts')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                return view('errors.403');
            }
        }
		$module = Module::get('Experts');
		
		if(Module::hasAccess($module->id)) {
            $listingCols = $this->listing_cols;
            if(Entrust::hasRole('SUPER_ADMIN')) {
                array_push($listingCols, 'is_live');
            }
			$aSupplier = \App\Models\Supplier::getList();
			return View('la.experts.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $listingCols,
				'module' => $module,
				'user' => $user,
				'aSupplier' => $aSupplier
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new expert.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
        $user = Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Experts')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                return view('errors.403');
            }
        }
        $module = Module::get('Experts');

        if(Module::hasAccess($module->id,'create')) {
            $aSupplier = \App\Models\Supplier::getList();
            return View('la.experts.create', [
                'module' => $module,
                'aSupplier' => $aSupplier,
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Store a newly created expert in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Experts", "create")) {
		
			$rules = Module::validateRules("Experts", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
            $uploaddocid =  '[]';
            if($request->hasfile('original_cv_file')) {
                $file = $request->file('original_cv_file');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
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
                    $uploaddocid = '["'.$uploadCV->id.'"]';
                }
            }
            $profileId = null;
            if($request->hasfile('profile')) {
                $file = $request->file('profile');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
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
                    $profileId = $uploadCV->id;
                }
            }
			
			$insert_id = Module::insert("Experts", $request);
            if($expert = Expert::where('id','=',$insert_id)->first()) {
                $expert->youtube_embed = $request->youtube_embed;
                if($uploaddocid !=  '[]'){
                $expert->original_cv_file = $uploaddocid;
                }
                if($profileId) {
                    $expert->profile_image = $profileId;
                }
                $expert->is_live = 1;
                $expert->save();
            }
			\App\Models\Expert::saveSkills($insert_id,$request['skills']);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.experts.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified expert.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Experts", "view")) {
			
			$expert = Expert::find($id);
			if(isset($expert->id)) {
				$module = Module::get('Experts');
				$module->row = $expert;
				
				return view('la.experts.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
                    'deleteMessage' => $this->deleteMessage,
				])->with('expert', $expert);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("expert"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified expert.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
        $user = Auth::user();
		if(Module::hasAccess("Experts", "edit")) {			
			$expert = Expert::with('upload')->find($id);
			if(isset($expert->id)) {	
				$module = Module::get('Experts');
				
				$module->row = $expert;

				$expert['skills_id'] = $expert->skills()->pluck('skill_id')->toArray();
				$aSkill = \App\Models\Skill::getAllSkills()->pluck('keyword', 'id');
				$aSupplier = \App\Models\Supplier::getList();

				return view('la.experts.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
					'aSkill' => $aSkill,
					'aSupplier' => $aSupplier,
					'user' => $user
				])->with('expert', $expert);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("expert"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified expert in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Experts", "edit")) {
			
			$rules = Module::validateRules("Experts", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}

            $uploaddocid =  '[]';
            if($request->hasfile('original_cv_file')) {
                $file = $request->file('original_cv_file');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
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
                    $uploaddocid = '["'.$uploadCV->id.'"]';
                }
            }
            $profileId = null;
            if($request->hasfile('profile')) {
                $file = $request->file('profile');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
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
                    $profileId = $uploadCV->id;
                }
            }
			
			$insert_id = Module::updateRow("Experts", $request, $id);
			\App\Models\Expert::saveSkills($id,$request['skills']);
			if($expert = Expert::where('id','=',$id)->first()) {
			    $expert->youtube_embed = $request->youtube_embed;
                if($uploaddocid !=  '[]'){
                $expert->original_cv_file = $uploaddocid;
                }
                if($profileId) {
                    $expert->profile_image = $profileId;
                }

			    $expert->save();
            }
			
			return redirect()->route(config('laraadmin.adminRoute') . '.experts.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified expert from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Experts", "delete")) {
			Expert::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.experts.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtAjax()
	{
        $listingCols = $this->listing_cols;
        if(\Entrust::hasRole('SUPER_ADMIN')) {
            array_push($listingCols, 'is_live');
        }
		$values = \App\Models\Expert::select($listingCols)->with(['skills','partner'])->whereNull('deleted_at');
        $user = \Auth::user();
        if(!empty($user) && $user->roles->first()->id == 2 && $user->company_id != 0) {
            $company_id = $user->company_id;
            $route = PartnerAccess::where('route', 'Experts')->where('user_id', $user->id)->first();
            if($route && $route->is_access == 0) {
                $values = $values->where('id',0);
            } elseif($route && $route->is_access == 1) {
                $values = $values->whereHas('partner', function($query) use ($company_id)  {
                    $query->where('company_id', $company_id);
                });
            }
        }
		$out = Datatables::of($values)->make();
		$data = $out->getData();
		$fields_popup = ModuleFields::getModuleFields('Experts');
		
		for($i=0; $i < count($data->data); $i++) {
            $id = $data->data[$i][0];
			for ($j=0; $j < count($listingCols); $j++) {
				$col = $listingCols[$j];

				if($listingCols[$j]=='skills'){
					$skillStr = [];
					foreach($data->data[$i][$j] as $skill){
						$skillStr[] = $skill->keyword;
					}
					$data->data[$i][$j] = implode(',', $skillStr);
				}elseif($listingCols[$j]=='partner'){
                    $partnerId = $data->data[$i][$j];
					$data->data[$i][$j] = trim($data->data[$i][$j]->partner_first_name);
				}elseif($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}elseif($listingCols[$j]=='description' || $this->listing_cols[$j]=='experience' || $this->listing_cols[$j]=='headline'){
					$data->data[$i][$j] = substr($data->data[$i][$j], 0 ,50).' <a href="'.url(config('laraadmin.adminRoute') . '/experts/'.$id.'/edit').'">read more...</a>';
				}
				
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/experts/'.$id).'">'.$data->data[$i][$j].'</a>';
				}
                if($col == 'is_live') {
                    $isLive = $data->data[$i][$j];
                    $className = ($isLive == "1") ?'btn-success':'btn-primary';
                    $isLive = $isLive? "Yes":"No";
                    $actionUrl = url(config('laraadmin.adminRoute') . '/expert-is-live', $id);
                    $button = "<a class='btn $className btn-xs is_live_btn' type='submit' href='$actionUrl' >$isLive</a>";
                    $data->data[$i][$j] = $button;
                }
                if($col == 'id') {
                    $data->data[$i][$j] = $data->data[$i][0];
                }
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Experts", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/experts/'.$id.'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Experts", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.experts.destroy', $id], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
                $coundcolumn = count($listingCols);
				$data->data[$i][$coundcolumn] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

		// Create API to add new expert CV from the website.

	public function addNewExpertCV(Request $request){

		$validator = Validator::make($request->all(), [
				'your-name' => 'required',
				'your-email' => 'required|email',
				'your-tel' => 'required',
				//'your-designation' => 'required',
//				'salary' => 'required',
				'cv' => 'required|mimes:pdf,doc,docx',
			]);
		if ($validator->fails()) 
        {
            $response = [
                'status' => false,
                'message' => "validation error",
                'errors' => $validator->errors(),
            ];
            return response()->json($response, Response::HTTP_OK);
        }
		if(Expert::where('email',$request['your-email'])->count() > 0 && Expert::where('skills',$request['your-designation'])->count() > 0){
            $response = [
                'status' => false,
                'message' => "validation error",
                'errors' => [
                    'your-email'=> ['Email already submitted'],
                ],
            ];
            return response()->json($response, Response::HTTP_OK);
		}else{

			$first_name = $request['your-name'];
			$email = $request['your-email'];
			$headline = $request['your-designation'];
            if(empty($headline)){
                $headline = "Developer";
            }
			/*$countryCode = $request['your-countrycode'];
            $countryList = Country::getCountriesWithIso();
            if($countryCode)
            {
                if (array_key_exists($countryCode,Country::PHONE_CODE_LIST)){
                    $countryIso = strtolower(Country::PHONE_CODE_LIST[$countryCode]);

                    if (array_key_exists($countryIso,$countryList))
                    {
                        $countryID = $countryList[$countryIso];
                    } else {
                        $countryID = $countryList['af'];
                    }
                } else {
                    $countryCode = "93";
                }
            } else {
                $countryID = null;
                $countryCode = null;
            }*/

			$phone = $request['your-tel'];
            //$phone = "+".$countryCode.$phone;
			$monthly_rate = $request['salary'];
			$publish_type	= 'internal_only';
  
			if($request->hasfile('cv'))  
			{  
				$file=$request->file('cv');  
				$folder = storage_path('uploads');
				$fname= $file->getClientOriginalName();
				$extension=$file->getClientOriginalExtension(); 				
				$date_append = date("Y-m-d-His-");
				$file->move($folder, $date_append.$fname);
				$path =  $folder.DIRECTORY_SEPARATOR.$date_append.$fname;	
				$uploaddocid =  '[]'; 
				if(!empty($fname))
				{
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
                    while(true) {
                        $hash = strtolower(str_random(20));
                        if(!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
					$uploaddocid =  '["'.$uploadCV->id.'"]';
				}
				Expert::insert([
					'first_name' => $first_name, 
					'email' => $email,
					'phone' => $phone,
                    'country'      => null,
					'hourly_rate'  => $monthly_rate,
					'monthly_rate' => $monthly_rate,
					'publish_type' => $publish_type,
					'partner' => 1,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
					'original_cv_file' => $uploaddocid,
					'headline' => $headline,
					'is_live' => 0,
 				]);
                //create contact in mautic
                $settings = [
                    'userName' => env('MAUTIC_USERNAME'),
                    'password' => env('MAUTIC_PASSWORD')
                ];
                $initAuth = new ApiAuth();
                $auth = $initAuth->newAuth($settings, env('MAUTIC_AUTH_VERSION', 'BasicAuth'));
                $timeout = 10;
                $auth->setCurlTimeout($timeout);
                $contact_data = array(
                    'firstname' => $first_name,
                    'email' => $email,
                    'mobile' => $phone,
                    'user_type' => \Config::get('constant.USER_TYPE_CANDIDATE'),
                    'ipAddress' => $_SERVER['REMOTE_ADDR'],
                    //'overwriteWithBlank' => true,
                );

                $apiUrl = env('MAUTIC_BASE_URL');
                $api = new MauticApi();
                $contactApi = $api->newApi("contacts", $auth, $apiUrl);
                $contact = $contactApi->create($contact_data);
                $config = array(
                    'sendAs' => 'html',
                    'template' => 'on_cv_submit',
                    'subject' => 'Thank you for your interest in a career with Eternitech!',
                    'params' =>  ["name"=>$first_name,"headline"=>$headline],
                    'from' => [Config::get('constant.noReplyEmailAddress')],
                    'to' => [$email]
                );
                Message::sendSystemEmail($config);

                $response = [
                    'status' => true,
                    'message' => "Thank You for your interest in a career with Eternitech. We'll reach out to you directly if your qualifications and skills match our requirements.",
                    'errors' => (object)[],
                ];
                return response()->json($response, Response::HTTP_OK);
			}
			else
			{
                $response = [
                    'status' => false,
                    'message' => "validation error",
                    'errors' => [
                        'cv'=> ['The cv field is required.'],
                    ],
                ];
                return response()->json($response, Response::HTTP_OK);
			}  

			$photourl = url('/'.$fname);
            $response = [
                'status' => true,
                'message' => "Thank You for your interest in a career with Eternitech. We'll reach out to you directly if your qualifications and skills match our requirements.",
                'errors' => (object)[],
            ];
            return response()->json($response, Response::HTTP_OK);
		}
	}

    public function downloadCvForm(Request $request) {

        $status = false;

        $validator = Validator::make($request->all(), [
            'expert_id' => 'required',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
        ]);

        if ($validator->fails())
        {
            $response = [
                'status' => $status,
                'message' => "validation error",
                'errors' => $validator->errors(),
                'cv_file' => '',
            ];
            return response()->json($response, Response::HTTP_OK);
        }
        $getExpertNameQuery = DB::table('experts')
            ->where('id', $request->expert_id)
            ->get(['first_name', 'last_name'])->first();
        $parseData = $request->all();
        $parseData['project_name']        = $parseData['project_name']?:"lead generate from download CV form". ' of ' . $getExpertNameQuery->first_name .' '. $getExpertNameQuery->last_name;
        $parseData['project_description'] = $parseData['project_description']?:"lead generate from download CV form". ' of ' . $getExpertNameQuery->first_name .' '. $getExpertNameQuery->last_name;
        $parseData['channel']             = $parseData['channel']?:BaseChannel::CRM;
        $parseData['source']              = Project::SOURCE_DOWNLOAD_CV_LEAD;
        $parseData['page_name']   		  = $parseData['page_name']?:"";
        $parseData['menu_name']           = $parseData['menu_name']?:"";
        $insertProject = Project::createProjectWithCompany($parseData);

        $message = "expert cv not found";
        $cvfile = "";

        if($expert = Expert::where('id','=',$request->expert_id)->first()) {

            $originalCvFile = json_decode($expert->original_cv_file,true);

            if(count($originalCvFile) != 0) {
                $status = true;
                $message = "expert cv found";
                $upload = Upload::where("id", $originalCvFile[0])->first();
                $cvfile = $upload->path();
                $cvfilePath = $upload->path;
                $fullName = $parseData['first_name']." ".$parseData['last_name'];
                $expertName = $getExpertNameQuery->first_name?:"";
                $email = $parseData['email'];
                $config = array(
                    'sendAs' => 'html',
                    'template' => 'download_cv',
                    'subject' => 'Thank you for your interest',
                    'params' =>  ["name"=>$fullName,"expertName"=>$expertName,'cvfile' => $cvfile],
                    'from' => [Config::get('constant.noReplyEmailAddress')],
                    'to' => [$email],
                    'attachments' => [$cvfilePath],
                );
                Message::sendSystemEmail($config);
            }
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'cv_file' => $cvfile,
        ];

        return response()->json($response, Response::HTTP_OK);

    }

     public function isLive(Request $request,$id) {
        $expert = Expert::with('partner')->find($id);
         $isLive = "No";
         $status = false;
        if($expert) {
            $status = true;
            if ($expert->is_live == 1) {
                $expert->is_live = 0;
            } else {
                $expert->is_live = 1;
                $isLive = "Yes";
                if($expert->partner && $expert->partner->partner_email){
                    $params = [
                        'partnerName' => $expert->partner->partner_first_name,
                        'portfolioTitle' => $expert->first_name?:'',
                        'createDate' => $expert->created_at->todatestring(),
                    ];
                    $config = array(
                        'template' => 'expert_approve',
                        'subject' => "Expert Approved",
                        'params' => $params,
                        'from' => [LAConfigs::getByKey('default_email')],
                        'to' => [$expert->partner->partner_email]
                    );
                    Message::sendSystemEmail($config);
                }
            }
            $expert->save();
            $response = [
                'status' => $status,
                'message' => "Expert updated successfully.",
                'isLive' => $isLive,
            ];

            return response()->json($response, Response::HTTP_OK);
        }
         $response = [
             'status' => $status,
             'message' => "nothing to update.",
             'isLive' => $isLive,
         ];

         return response()->json($response, Response::HTTP_OK);
     }
}
