<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use App\Models\Country;

class CountriesController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'iso', 'description', 'flag'];
	
	public function __construct() {
        parent::__construct();
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Countries', $this->listing_cols);
				return $next($request);
			});
		} else {
            $this->middleware(function ($request, $next) {
            $this->listing_cols = ModuleFields::listingColumnAccessScan('Countries', $this->listing_cols);
            return $next($request);
            });
		}
	}
	
	/**
	 * Display a listing of the Countries.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        $user = Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
            return view('errors.403');
        }
		$module = Module::get('Countries');
		
		if(Module::hasAccess($module->id)) {
			return View('la.countries.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module,
				'user' => $user
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new country.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
        $user = Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
            return view('errors.403');
        }
        $module = Module::get('Countries');
        if(Module::hasAccess($module->id,'create')) {
            return View('la.countries.create', ['module' => $module]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Store a newly created country in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Countries", "create")) {
		
			$rules = Module::validateRules("Countries", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Countries", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.countries.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified country.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Countries", "view")) {
			
			$country = Country::find($id);
			if(isset($country->id)) {
				$module = Module::get('Countries');
				$module->row = $country;
				
				return view('la.countries.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
                    'deleteMessage' => $this->deleteMessage,
				])->with('country', $country);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("country"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified country.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Countries", "edit")) {			
			$country = Country::find($id);
			if(isset($country->id)) {	
				$module = Module::get('Countries');
				
				$module->row = $country;
                $user = Auth::user();
				return view('la.countries.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
					'user' => $user,
				])->with('country', $country);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("country"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified country in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Countries", "edit")) {
			
			$rules = Module::validateRules("Countries", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Countries", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.countries.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified country from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Countries", "delete")) {
			Country::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.countries.index');
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
		$values = DB::table('countries')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Countries');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/countries/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Countries", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/countries/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Countries", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.countries.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
