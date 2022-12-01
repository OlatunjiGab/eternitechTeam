<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Models\PartnerAccess;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Datatables;
use Session;
use Entrust;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use App\Models\Supplier;
use App\User;

class SuppliersController extends Controller
{
	public $show_action = true;
	public $view_col = 'company_id';
	public $listing_cols = ['id', 'company_id', 'closing_rate', 'avg_response_time', 'hourly_rate'];
	public $show_view_col = 'name';
	public $show_view_email_col = 'partner_email';
	public $show_listing_cols = ['id', 'name', 'email', 'portfolios', 'experts', 'closing_rate', 'avg_response_time', 'hourly_rate'];
	
	public function __construct() {
        parent::__construct();
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Suppliers', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Suppliers', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Suppliers.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        if(Entrust::hasRole("PARTNER") && !Auth::user()->canAccess()) {
            if(!Session::has('partnerPopupShow')) {
                Session::put('partnerPopupShow', 1);
            }
        }
        $user = \Auth::user();
        if(!Auth::user()->canAccess()) {
            return view('errors.403');
        }
        $user = \Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Partners')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                return view('errors.403');
            }
        }
		$module = Module::get('Suppliers');
		
		if(Module::hasAccess($module->id)) {
			return View('la.suppliers.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->show_listing_cols,
				'module' => $module,
                'user'   => $user,
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new supplier.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created supplier in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Suppliers", "create")) {
		
			$rules = Module::validateRules("Suppliers", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Suppliers", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.partners.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified supplier.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Suppliers", "view")) {
            $user = \Auth::user();
			$supplier = Supplier::find($id);
            $user = Auth::user();
            $hasAccess = true;
            if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
                if($user->company_id != $supplier->company_id) {
                    $hasAccess = false;
                }
            }
			if(isset($supplier->id) && $hasAccess) {
                $UserSupplierDept = User::select('employees.dept as dept')
                    ->where('users.id',auth()->user()->id)
                    ->leftJoin('employees', 'employees.id', '=', 'users.id')
                    ->first();
                $UserSupplierID = Supplier::select('users.id as user_id')
                    ->where('suppliers.id',$id)
                    ->leftJoin('users', 'users.supplier_id', '=', 'suppliers.id')
                    ->first();
				$module = Module::get('Suppliers');
				$module->row = $supplier;
				$company = $supplier->company()->first();
		  		$userRole = Auth::user()->role_user()->first()->role_id;
				return view('la.suppliers.show', [
					'UserSupplierDept' => $UserSupplierDept,
					'UserSupplierID' => $UserSupplierID,
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'user_role' => $userRole,
					'company' => $company,
                    'deleteMessage' => $this->deleteMessage,
                    'user' => $user,
				])->with('supplier', $supplier);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("supplier"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified supplier.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Suppliers", "edit")) {			
			$supplier = Supplier::find($id);
            $user = \Auth::user();
			if(isset($supplier->id)) {	
				$module = Module::get('Suppliers');
				
				$module->row = $supplier;
				
				$supllierSkill = \App\Models\SupplierSkill::where('supplier_id',$id)->pluck('skill_id')->toArray();

				$skills = \App\Models\Skill::where('deleted_at',null)->pluck('keyword', 'id')->toArray();
				return view('la.suppliers.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
					'supllierSkill' => $supllierSkill,
					'aSkill' => $skills,
					'user' => $user,
				])->with('supplier', $supplier);

			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("supplier"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified supplier in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Suppliers", "edit")) {
			
			$rules = Module::validateRules("Suppliers", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}

			$skill = $request->all()['skill'];
			$supplierSkill = \App\Models\SupplierSkill::where('supplier_id',$id)->pluck('skill_id','id')->toArray();
			$removeSkill = array_diff($supplierSkill, $skill);
			$saveSkill = array_diff($skill,$supplierSkill);

			if(!empty($removeSkill)){
				\App\Models\SupplierSkill::where('supplier_id',$id)->whereIn('skill_id',$removeSkill)->delete();
			}

			if(!empty($saveSkill)){
				$aSaveData = array_map(function($val)use($id){return ['supplier_id'=>$id,'skill_id'=>$val];},$saveSkill);
				\App\Models\SupplierSkill::insert($aSaveData);
			}

			$insert_id = Module::updateRow("Suppliers", $request, $id);
			
			//return redirect()->route(config('laraadmin.adminRoute') . '.suppliers.index');
			session()->flash('success','Supplier has been updated successfully.');
			//session()->flash('msg','Hey, You have a message to read');
			return back();
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified supplier from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Suppliers", "delete")) {
			Supplier::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.partners.index');
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
		//DB::raw("CONCAT('partner_first_name','partner_last_name') AS name")		
		$userRole = Auth::user()->role_user()->first()->role_id;
		$partnerNotification = DB::raw('"these communication details are hidden to avoid and reduce spam." AS name');
		if($userRole == 2){
			//$partnerEmailData = $partnerNotification;
            $partnerEmailData = 'suppliers.partner_email';
		}else{
			$partnerEmailData = 'suppliers.partner_email';
		}
		$values = Supplier::select('suppliers.id','suppliers.partner_first_name',$partnerEmailData,'suppliers.closing_rate','suppliers.avg_response_time','suppliers.hourly_rate')->whereNull('suppliers.deleted_at');
		//$values = DB::table('suppliers')->select('suppliers.id','suppliers.partner_first_name','suppliers.partner_email','suppliers.closing_rate','suppliers.avg_response_time','suppliers.hourly_rate')->whereNull('suppliers.deleted_at');
        $user = \Auth::user();
        if(!empty($user) && $user->roles->first()->id == 2 && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Partners')->where('user_id', $user->id)->first();
            if($route && $route->is_access == 0) {
                $values = $values->where('id',0);
            } elseif($route && $route->is_access == 1) {
                $values = $values->where('company_id',$user->company_id);
            }
        }
		$values = $values->orderBy('suppliers.id','DESC');
		$values = $values->join('companies', 'companies.id', '=', 'suppliers.company_id'); 
		$out = Datatables::of($values)->add_column('portfolios', function($value) {
            return $value->portfolios->count();
        })->add_column('experts', function($value) {
            return $value->experts->count();
        })->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Suppliers');
		
		for($i=0; $i < count($data->data); $i++) {
 			 
		    $UserSupplierID = Supplier::select('users.id as user_id')
             ->where('suppliers.id',$data->data[$i])
             ->leftJoin('users', 'users.supplier_id', '=', 'suppliers.id')
             ->first();


		    $UserSupplierDept = User::select('employees.dept as dept')  
			     ->where('users.id',auth()->user()->id)
			     ->leftJoin('employees', 'employees.id', '=', 'users.id') 
		         ->first();

			for ($j=0; $j < count($this->show_listing_cols); $j++) { 
				$col = $this->show_listing_cols[$j];
				if($col == $this->show_view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/partners/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}

				if($col == $this->show_view_email_col) {
					$data->data[$i][$j] = $data->data[$i][$j];
				}
                if($col == "portfolios") {
					$data->data[$i][$j] = $data->data[$i][6];
				}
                if($col == "experts") {
					$data->data[$i][$j] = $data->data[$i][7];
				}
			}
			
			if($this->show_action) {
				$output = '';
				
				if($UserSupplierDept->dept == 1) {
                    if(PartnerAccess::where('user_id','=',$UserSupplierID->user_id)->where('is_access','!=',0)->exists()){
                        $output .= '<a href="/'.config('laraadmin.adminRoute').'/users/'.$UserSupplierID->user_id.'#tab-access"><span class="bg-success" title="Reviewed"><i class="fa fa-check-circle-o"></i></span></a> &nbsp';
                    } else {
                        $output .= '<a href="/'.config('laraadmin.adminRoute').'/users/'.$UserSupplierID->user_id.'#tab-access"><span class="bg-danger" title="Not Reviewed"><i class="fa fa-ban"></i></span></a> &nbsp';
                    }
				$output .= '<a href="/impersonate/user/'.$UserSupplierID->user_id.'" class="btn btn-success btn-xs" style="margin-right: 3px;">Impersonate</a>';
				}
				
				if(Module::hasAccess("Suppliers", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/partners/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Suppliers", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.partners.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
 

				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

	/*
	* filter 
	*
	*/

	public function PartnersBySkills()
	{	
		//$aRowSupplierSkills = \App\Models\SupplierSkill::with('supplier')->orderBy('id', 'desc');				
		//$aRowSupplierSkills = $aRowSupplierSkills->get();

		return View('la.suppliers.partner-by-skills');
		
	}

	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function PartnersBySkillsAJAX($id = null)
	{	
		//DB::raw("CONCAT('partner_first_name','partner_last_name') AS name")		
		$values = \App\Models\SupplierSkill::select('supplier_skills.id','companies.name','skills.keyword','experience','rate','comment','supplier_id');
		$values = $values->orderBy('supplier_skills.id','DESC');
		$values = $values->join('suppliers', 'suppliers.id', '=', 'supplier_skills.supplier_id');
		$values = $values->join('skills', 'skills.id', '=', 'supplier_skills.skill_id');
		$values = $values->join('companies', 'companies.id', '=', 'suppliers.company_id');
		if(isset($id) && !empty($id)){
		    $values = $values->where('suppliers.id',$id);
        }
		$out = Datatables::of($values)->make();
		$data = $out->getData();


		//$fields_popup = ModuleFields::getModuleFields('Suppliers');		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->show_listing_cols); $j++) { 
				$col = $this->show_listing_cols[$j];
				
				if($col == $this->show_view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/partners/'.$data->data[$i][6]).'">'.$data->data[$i][$j].'</a>';
				}

				if($col == $this->show_view_email_col) {
					$data->data[$i][$j] = $data->data[$i][$j];
				}
			}
		}
		$out->setData($data);
		return $out;
	}

}
