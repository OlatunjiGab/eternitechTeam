<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Datatables;
use Entrust;
use Session;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use App\Models\Company;
use App\Models\PartnerAccess;

class CompaniesController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'email', 'homepage', 'address', 'address2', 'city', 'state', 'country', 'zipcode', 'phone', 'fax', 'logo_url', 'language', 'channel', 'strategic','is_banned'];
	public $contact_cols = ['id', 'first_name','last_name', 'email', 'phone','language'];
	
	public function __construct() {
        parent::__construct();
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Companies', $this->listing_cols);
				return $next($request);
			});
		} else {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ModuleFields::listingColumnAccessScan('Companies', $this->listing_cols);
                return $next($request);
            });
		}
	}
	
	/**
	 * Display a listing of the Companies.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
        if(Entrust::hasRole("PARTNER")) {
            if(!Session::has('partnerPopupShow')) {
                Session::put('partnerPopupShow', 1);
            }
        }
        $user = \Auth::user();
        $this->listing_cols = array_merge($this->listing_cols,['created_at']);
        if(!Auth::user()->canAccess()) {
            return view('errors.403');
        }
        $user = \Auth::user();
        $languageList = Language::getLanguageList();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Prospects')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                return view('errors.403');
            }
        }
		$module = Module::get('Companies');
        $countriesList = Country::getCountries();
        $companyTypeList = Company::TYPE_LIST;
        unset($companyTypeList[Company::TYPE_IT_FIRM]);
        unset($companyTypeList[Company::TYPE_PROJECT_MANAGER]);

		if(Module::hasAccess($module->id)) {
			return View('la.companies.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'languageList' => $languageList,
				'countriesList' => $countriesList,
				'module' => $module,
				'companyTypeList' => $companyTypeList,
                'user'            => $user,
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new company.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
        if(!Auth::user()->canAccess()) {
            return view('errors.403');
        }
        $user = Auth::user();
        $languageList = Language::getLanguageList();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Prospects')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                return view('errors.403');
            }
        }
        $module = Module::get('Companies');
        $countriesList = Country::getCountries();
        $companyTypeList = Company::TYPE_LIST;
        unset($companyTypeList[Company::TYPE_IT_FIRM]);
        unset($companyTypeList[Company::TYPE_PROJECT_MANAGER]);

        if(Module::hasAccess($module->id, "create")) {
            return View('la.companies.create', [
                'languageList' => $languageList,
                'countriesList' => $countriesList,
                'module' => $module,
                'companyTypeList' => $companyTypeList,
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Store a newly created company in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Companies", "create")) {
		
			$rules = Module::validateRules("Companies", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Companies", $request);
            if($request->type && $company = Company::where('id',$insert_id)->first()) {
                $company->type = $request->type;
                $company->strategic = $request->strategic? 1 : 0;
                $company->save();
            }
			
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified company.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Companies", "view")) {
			
			$company = Company::select(['companies.*',\DB::raw('IF(suppliers.id IS NULL,0,1) isSupplier')])->
								leftJoin('suppliers', 'companies.id', '=', 'suppliers.company_id')->find($id);
            $user = Auth::user();
            $hasAccess = true;
            if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
                if($user->company_id != $company->id) {
                    $hasAccess = false;
                }
            }
            if(isset($company->id) && $hasAccess) {
				$module = Module::get('Companies');
				$module->row = $company;
				
				$modulep = Module::get('Projects');
                $user = \Auth::user();
				
				$aData = [
					'user' => $user,
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'project_actions' => false,
					'projects_cols' => (new ProjectsController())->listing_cols,
					'modulep' => $modulep,
					'contact_cols' => $this->contact_cols,
					'contact_actions' => true,
					'deleteMessage' => $this->deleteMessage,
				];

				return view('la.companies.show', $aData)->with('company', $company);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("Prospect"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified company.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Companies", "edit")) {			
			$company = Company::select(['companies.*',\DB::raw('IF(suppliers.id IS NULL,0,1) isSupplier')])->
								leftJoin('suppliers', 'companies.id', '=', 'suppliers.company_id')->find($id);

            $user = Auth::user();
            $hasAccess = true;
            if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
                if($user->company_id != $company->id) {
                    $hasAccess = false;
                }
            }
            if(isset($company->id) && $hasAccess) {
				$module = Module::get('Companies');
				
				$module->row = $company;

				$skills = \App\Models\Skill::where('deleted_at',null)->pluck('keyword', 'id')->toArray();
                $languageList = Language::getLanguageList();
                $countriesList = Country::getCountries();
                $companyTypeList = Company::TYPE_LIST;
                unset($companyTypeList[Company::TYPE_IT_FIRM]);
                unset($companyTypeList[Company::TYPE_PROJECT_MANAGER]);

				return view('la.companies.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
					'languageList' => $languageList,
					'countriesList' => $countriesList,
					'aSkill' => $skills,
					'companyTypeList' => $companyTypeList,
				])->with('company', $company);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("Prospect"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified company in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Companies", "edit")) {

			$pData = $request->all();
            $request->strategic = isset($request->strategic)?1:0;
			
			$rules = Module::validateRules("Companies", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Companies", $request, $id);
            if($request->type && $company = Company::where('id',$id)->first()) {
                $company->type = $request->type;
                $company->plan_type = $request->plan_type;
                $company->save();
            }
			
			if(isset($pData['is_supplier'])){
				$supplierSave = $request->all()['supplier'];
				$supplierSave['company_id'] = $id;
				
				$findData = \App\Models\Supplier::where($supplierSave)->first();
				if(empty($findData)){
					$supplierSave = \App\Models\Supplier::create($supplierSave);
					$supplierSkills = array_map(function($val) use($supplierSave){return ['skill_id'=>$val,'supplier_id'=>$supplierSave->id];},$pData['skill']);
					$supplierSaveSkills = $supplierSave->supplierSkills()->insert($supplierSkills);
					//$supplierSaveSkills = $supplierSave->supplier_skills()->associate($supplierSkills)->save();
				}
			}
			
			return redirect(config('laraadmin.adminRoute') . '/companies/' . $id);
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified company from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Companies", "delete")) {
			Company::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtAjax(Request $request)
	{
        $this->listing_cols = array_merge($this->listing_cols,['created_at']);
		$values = DB::table('companies')->select($this->listing_cols)->whereNull('deleted_at');
        $is_partner = 0;
		$user = \Auth::user();
        if(!empty($user) && $user->roles->first()->id == 2 && $user->company_id != 0) {
            $is_partner = 1;
            $route = PartnerAccess::where('route', 'Prospects')->where('user_id', $user->id)->first();
            if($route && $route->is_access == 0) {
                $values = $values->where('id',0);
            } elseif($route && $route->is_access == 1) {
                $values = $values->where('id',$user->company_id);
            }
        }
        if (isset($request['columns'][16]['search']['value']) && !empty($request['columns'][16]['search']['value'])) {
            $dateRange = explode("to",$request['columns'][16]['search']['value']);
            $fromDate = $dateRange[0];
            $toDate = $dateRange[1];
            $values = $values->whereDate("created_at", ">=", $fromDate)
                ->whereDate("created_at", "<=", $toDate);
        }
		$values = $values->orderBy('companies.id','DESC');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Companies');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
                if($col == 'language') {
                    $data->data[$i][$j] = Language::getLanguageNameByCode($data->data[$i][$j]);
                }
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/companies/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
			
					if($data->data[$i][16]==0){
						$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/companies/banned_allowd_bidding/'.$data->data[$i][0]) .'" class="btn btn-sm btn-success allow-ban-btn" title="if you want ban this client for bidding, click here"> Allowed </a>';
					} else{
						$output .='<a href="'.url(config('laraadmin.adminRoute') . '/companies/banned_allowd_bidding/'.$data->data[$i][0]) .'" class="btn btn-sm btn-danger allow-ban-btn" title="if you want allow this client for bidding click here"> Banned </a>';
					}
                $output = $is_partner ? '' : $output;
					
				
				//$output .='<a href="'.url(config('laraadmin.adminRoute') . '/companies/banned_allowd_bidding/'.$data->data[$i][0]) .'" class="btn btn-sm btn-danger" title="if you want allow this client for bidding click here">'. $data->data[$i][16]==1 ? "Banned" : "Allowed" .'</a>';	

				if(Module::hasAccess("Companies", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/companies/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Companies", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.companies.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function contactAjax(Request $request)
	{
		$values = DB::table('company_contacts')->select($this->contact_cols);
		if(isset($request->all()['company_id'])){
			$values = $values->where('company_id',$request->all()['company_id']);
		}
		$values = $values->orderBy('company_contacts.id','DESC');
		
		$out = Datatables::of($values)->make();
		$data = $out->getData();
		
		for($i=0; $i < count($data->data); $i++) {
			if($this->show_action) {
				$output = '';
				/*if(Module::hasAccess("Companies", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/companies/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Companies", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.companies.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}*/
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

	/**
	 * banned/allowed bidding the specified company .
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function bannedAllowedForBidding($id="")
	{
		if (!empty($id)) {
			$aRowCompanyData = Company::where(['id'=>$id])->first();
			if (isset($aRowCompanyData) && !empty($aRowCompanyData)) {
				$aRowCompanyData = $aRowCompanyData->toArray();
				$IsBanned = $aRowCompanyData['is_banned']==0 ? 1 : 0 ;
				Company::where(['id'=>$id])->update(['is_banned'=>$IsBanned]);

				if ($IsBanned == 0) {
					\Session::flash('success', ' Client allowed.');					
				} else {
					\Session::flash('success', ' Client banned.');
				}
				return redirect()->back();
			}
		}
	}

    public function updateClient(Request $request, $id)
    {
        if($company = Company::where('id',$id)->first()) {
            $company->name     = $request->name?:'';
            $company->email    = $request->email?:'';
            $company->phone    = $request->phone?:'';
            $company->homepage = $request->homepage?:'';
            $company->save();
            \Session::flash('success', 'updated details');
        }
        return redirect()->back();
    }
}
