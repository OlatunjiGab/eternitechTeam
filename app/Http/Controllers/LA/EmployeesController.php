<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use Dwij\Laraadmin\Helpers\LAHelper;

use App\User;
use App\Models\Employee;
use App\Models\PartnerAccess;
use App\Role;
use Mail;
use Log;
use LAConfigs;
use App\Helpers\CustomHelper;

class EmployeesController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'designation', 'mobile', 'email', 'dept'];
	public $routes = ['Prospects','Partners','Leads','Experts'];
	
	public function __construct() {
        parent::__construct();
		
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Employees', $this->listing_cols);
				return $next($request);
			});
		} else {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ModuleFields::listingColumnAccessScan('Employees', $this->listing_cols);
                return $next($request);
            });
		}
	}
	
	/**
	 * Display a listing of the Employees.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        $user = Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
            return view('errors.403');
        }
		$module = Module::get('Employees');
        $countriesList = Country::getCountries();
		
		if(Module::hasAccess($module->id)) {
			return View('la.employees.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
                'module'       => $module,
                'countriesList'=> $countriesList,
                'user'=> $user,
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new employee.
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
        $module = Module::get('Employees');
        $countriesList = Country::getCountries();

        if(Module::hasAccess($module->id,'create')) {
            return View('la.employees.create', ['module' => $module, 'countriesList' => $countriesList]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Store a newly created employee in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Employees", "create")) {
		
			$rules = Module::validateRules("Employees", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			// generate password
			$password = LAHelper::gen_password();
			// Create Employee
			$employee_id = Module::insert("Employees", $request);
			// Create User
            if($request->has('profile_picture')) {
                $imagePath = $request->file('profile_picture')->store('public/images');
                $fileName = $request->file('profile_picture')->hashName();
            }
            else{
                $fileName = 'default-avatar.jpg';
            }
            $uData = [
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($password),
				'context_id' => $employee_id,
				'type' => "Employee",
                'profile_picture' => $fileName,
			];

            if(isset($request->company_id) && !empty($request->company_id)){
                $uData['company_id'] = $request->company_id;
            }
            $user = User::create($uData);

            // update user role
			$user->detachRoles();
			$role = Role::find($request->role);
			$user->attachRole($role);
			
			if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
				// Send mail to User his Password

				$isEmailBounce = $user->company()->select('is_email_bounce')->first();
	     		if (!$isEmailBounce['is_email_bounce']) {
	     			Mail::send('emails.send_login_cred', ['user' => $user, 'password' => $password], function ($m) use ($user) {
						$m->from('hello@laraadmin.com', 'LaraAdmin');
						$m->to($user->email, $user->name)->subject('Eternitech Partner Network - Your Login Credentials');
					});
					$emailBounced = CustomHelper::checkAndUpdateBounceFlagEmail($user->email, $user->company_id);
	     		}
				
				// Message::sendEmail(array(
				//    'template' => 'send_login_cred',
    //                'subject' => 'LaraAdmin - Your Login Credentials',
    //                'params' => ['user' => $user, 'password' => $password],
    //                'to' => ['email' => $user->email, 'name' => $user->name]
    //             ));

			} else {
				Log::info("User created: username: ".$user->email." Password: ".$password);
			}
			
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Employees", "view")) {
            $employee = Employee::find($id);
            if(isset($employee->id)) {
                $module = Module::get('Employees');
                $module->row = $employee;

                // Get User Table Information
                $user = User::where('context_id', '=', $id)->firstOrFail();
                $query = DB::table('partner_access')->where('user_id', $user->id)->get();
                if($query->count() <= 0) {
                    foreach($this->routes as $route) {
                        $PartnerAccess = new PartnerAccess;
                        $PartnerAccess->route = $route;
                        $PartnerAccess->is_access = 1;
                        $PartnerAccess->user_id = $user->id;
                        $PartnerAccess->created_at = date('Y-m-d H:i:s');
                        $PartnerAccess->updated_at = date('Y-m-d H:i:s');
                        $PartnerAccess->save();
                    }
                    $query = DB::table('partner_access')->where('user_id', $user->id)->get();
                }
                $user = Auth::user();
                return view('la.employees.show', [
					'user' => $user,
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'routes'=>$query,
					'user_id'=>$user->id,
                    'deleteMessage' => $this->deleteMessage,
                    'user' => $user,
				])->with('employee', $employee);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("employee"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Employees", "edit")) {

			$employee = Employee::find($id);
            $user = \Auth::user();
            if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->context_id != $employee->id) {
                return view('errors.403');
            }
			if(isset($employee->id)) {
				$module = Module::get('Employees');
                $module->row = $employee;

                // Get User Table Information
				$user = User::where('context_id', '=', $id)->firstOrFail();
                $countriesList = Country::getCountries();
                $user = Auth::user();
				return view('la.employees.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
					'user' => $user,
					'countriesList' => $countriesList,
				])->with('employee', $employee);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("employee"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified employee in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Employees", "edit")) {
			
			$rules = Module::validateRules("Employees", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$employee_id = Module::updateRow("Employees", $request, $id);
        	
			// Update User
			$user = User::where('context_id', $employee_id)->first();

			if(isset($request->name) && !empty($request->name)) {
                $user->name = $request->name;
            }
            if(isset($request->email) && !empty($request->email)) {
                $user->email = $request->email;
            }
            if(isset($request->company_id) && !empty($request->company_id)) {
                $user->company_id = $request->company_id;
            }
            if($request->has('profile_picture')) {
                $userPhoto = rtrim(public_path('storage\images\ ')).$user->profile_picture;
                if(file_exists($userPhoto)){
                    @unlink($userPhoto); // then delete previous photo
                }
                $imagePath = $request->file('profile_picture')->store('public/images');
                $fileName = $request->file('profile_picture')->hashName();
                $user->profile_picture = $fileName;
            }
            $user->save();

            if(isset($request->role) && !empty($request->role)) {
                // update user role
                $user->detachRoles();
                $role = Role::find($request->role);
                $user->attachRole($role);
            }
            \Session::flash('success', 'Details updated...');
            $loginUser = \Auth::user();
            if($loginUser->roles->first()->name == 'PARTNER') {
                return redirect(config('laraadmin.adminRoute') . "/employees/".$id);
            } else {
                return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
            }
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified employee from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Employees", "delete")) {
			Employee::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
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
		$values = DB::table('employees')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Employees');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/employees/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Employees", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/employees/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Employees", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.employees.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
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
     * Change Employee Password
     *
     * @return
     */
	public function changePassword($id, Request $request) {
		
		$validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password'
        ]);
		
		if ($validator->fails()) {
			return \Redirect::to(config('laraadmin.adminRoute') . '/employees/'.$id)->withErrors($validator);
		}
		
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->where('type', 'Employee')->first();
		$user->password = bcrypt($request->password);
		$user->save();
		
		\Session::flash('success_message', 'Password is successfully changed');
		
		// Send mail to User his new Password
		if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
			// Send mail to User his new Password
			$isEmailBounce = $user->company()->select('is_email_bounce')->first();
     		if (!$isEmailBounce['is_email_bounce']) {
     			Mail::send('emails.send_login_cred_change', ['user' => $user, 'password' => $request->password], function ($m) use ($user) {
					$m->from(LAConfigs::getByKey('default_email'), LAConfigs::getByKey('sitename'));
					$m->to($user->email, $user->name)->subject('Eternitech Partner Network - Login Credentials Changed');
				});
				$emailBounced = CustomHelper::checkAndUpdateBounceFlagEmail($user->email, $user->company_id);
     		}
			

            // Message::sendEmail(array(
            //     'template' => 'send_login_cred_change',
            //     'subject' => 'LaraAdmin - Login Credentials chnaged',
            //     'params' =>  ['user' => $user, 'password' => $request->password],
            //     'to' => ['email' => $user->email, 'name' => $user->name]
            // ));

        } else {
			Log::info("User change_password: username: ".$user->email." Password: ".$request->password);
		}
		
		return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-account-settings');
	}
}
