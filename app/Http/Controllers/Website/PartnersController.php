<?php

namespace App\Http\Controllers\Website;

use App\Channels\BaseChannel;
use App\Classes\Cache;
use App\Classes\Slack;
use App\Helpers\Curl;
use App\Helpers\SystemResponse;
use App\Http\Controllers\Controller;

use App\Jobs\ScraperContinueJob;
use App\Models\Country;
use App\Models\PartnerAccess;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Company;
use App\Models\Skill;
use App\Models\SupplierSkill;
use Dwij\Laraadmin\Helpers\LAHelper;
use App\User;
use App\Models\Employee;
use App\Role;
use Mail;
use Auth;
use DB;
use Config;
use App\Helpers\CustomHelper;


class PartnersController extends Controller
{
    /**
     * show partner register steps page
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function partnerRegistrationFrom(Request $request)
	{
		$data = $request->input();
		$aRowPartnerData = [];
		$aRowAgencyData = [];
        $aRowEmployeeData = [];
        $typeList = Company::TYPE_LIST;
        unset($typeList[Company::TYPE_IT_FIRM]);
        unset($typeList[Company::TYPE_PROJECT_MANAGER]);
        $countriesList = Country::getCountries();
		try{	
			
			if (!empty($data['fname'])) {

                $email = $data['email'];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = "$email is not a valid email address";
                    echo '<form action="https://eternitech.com/partners" method="post" id="errorform">
							<input type="hidden" name="error" value="'.$message.'">
							</form>';
                    echo "<script>document.getElementById('errorform').submit();</script>";
                    return;
                }
				$aEmailExists = Supplier::where(['partner_email'=>$data['email'] ])->first();
				if (!$aEmailExists) {
					// insert data into Supplier
					$partnerId =Supplier::insertGetId(
					    [
					    	'partner_first_name'=>$data['fname'].' '.$data['lname'],
					    	'partner_last_name'=>$data['lname'], 
					    	'partner_email'=>$data['email'], 
					    ]
					);
					
				   $employee_id = Employee::create([
						'name' => $data['fname'].' '.$data['lname'],
						'email' => $data['email'],
					  	'dept' => 2,
                        'date_hire' => date("Y-m-d"),
						]);
 
					$password = LAHelper::gen_password();
					$uData = [
							'name' => $data['fname'].' '.$data['lname'],
							'email' => $data['email'],
							'password' => bcrypt($password),
							'supplier_id' => $partnerId,
							'context_id' => $employee_id->id, 
				  			'type' => "Employee"
						];	
 					$user = User::create($uData);

                    if($employee_id->id && $user->id) {
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
                        }
                    }

					// update user role
					$user->detachRoles();
					$role = Role::find(2);
					$user->attachRole($role);


                    $companyType = $data['type']?: Company::TYPE_AGENCY;
				 	$request->session()->put('userId', $user->id);
				 	$request->session()->put('employeeId', $employee_id->id);
					$request->session()->put('step_1', 1);
					$request->session()->put('partnerId', $partnerId);
					$request->session()->put('password', $password);
					$request->session()->forget('companyId');
                    $request->session()->put('companyType', $companyType);
					return redirect('partner-registration');
				} 
				else
					{
						echo '<form action="https://eternitech.com/partners" method="post" id="errorform">
							<input type="hidden" name="error" value="Email already exists">
							</form>';
						echo "<script>document.getElementById('errorform').submit();</script>";
						return;
					}
				
			} else {
                if(empty($request->session()->get('userId'))) {
                    return redirect('https://eternitech.com/partners');
                }
            }

			if (!empty($data['agency_name']) && empty($request->session()->get('companyId'))) {
				$request->session()->put('step_3', 1);
                $usedDetails = User::where(['id'=>$request->session()->get('userId')])->first();
				// insert data into company	
				$companyId =Company::insertGetId(
				    [
				    	'name'=>$data['agency_name'], 
				    	'phone'=>$data['agency_phone'],
				    	'email'=>$usedDetails->email?:'',
				    	'agency_description'=>$data['agency_description'],
				    	'agency_website'=>$data['agency_website'], 
				    	//'address'=>$data['agency_address'],
				    	'city'=>$data['agency_city'],
				    	'state'=>$data['agency_state'],
				    	'zipcode'=>$data['agency_zipcode'],
				    	'country'=>$data['agency_country'],
				    	'type'=> $data['type']?: Company::TYPE_AGENCY,
				    ]
				);	

				// update supplier insert company id
				Supplier::where(['id'=>$request->session()->get('partnerId') ])->update(['company_id'=>$companyId]);
				User::where(['id'=>$request->session()->get('userId') ])->update(['company_id'=>$companyId]);
				$request->session()->put('step_2', 1);
				$request->session()->put('companyId', $companyId);

			} 

            if (!empty($data['employee_name']) && !empty($request->session()->get('employeeId'))) {
                $employeeData = [
                    'name'=>$data['employee_name'],
                    'gender'=>$data['employee_gender'],
                    'mobile'=>$data['employee_mobile'],
                    'mobile2'=>$data['employee_mobile2'],
                    //'address'=>$data['employee_address'],
                    'city'=>$data['employee_city'],
                    'state'=>$data['employee_state'],
                    'zipcode'=>$data['employee_zipcode'],
                    'country'=>$data['employee_country'],
                ];
                Employee::where(['id'=>$request->session()->get('employeeId') ])->update($employeeData);
            }

			if ($request->session()->get('employeeId')) {
                $aRowEmployeeData = Employee::where(['id' => $request->session()->get('employeeId')])->first();
            }
			if ($request->session()->get('partnerId')) {
				$aRowPartnerData = Supplier::where(['id'=>$request->session()->get('partnerId')])->first();
			}
			if ($request->session()->get('companyId')) {
				$aRowAgencyData = Company::where(['id'=>$request->session()->get('companyId')])->first();
			}	
			$aRowSkillData = Skill::select('id','keyword')->where('deleted_at', '=', null )->get(); // get skills data

			if ($request->session()->get('step_3')) {
				$request->session()->forget(['partnerId','companyId','step_1','step_2','step_3','employeeId']);
				return redirect('thank-you');
			}

			return view('partner-registration.register',['aRowPartnerData'=>$aRowPartnerData, 'aRowAgencyData'=>$aRowAgencyData, 'aRowSkillData'=>$aRowSkillData,'typeList' => $typeList, 'aRowEmployeeData' =>$aRowEmployeeData, 'countriesList'=>$countriesList, ]);
			
		}catch(Exception $e){
			return redirect()->back()->with('failed',"operation failed");
		}		
	}


    /**
     * skill form
     * @return string
     */
    public function getSkillHtml()
	{
		$supplierId = session()->get('partnerId');
		$aRowSupplierData = SupplierSkill::where(['supplier_id'=>$supplierId])->orderBy('updated_at','DESC')->get();
		$aRowSkillData = Skill::select('id','keyword')->where('deleted_at', '=', null )->get(); 
		$sHtml = (string) view('partner-registration.skill-form',['aRowSupplierData'=>$aRowSupplierData, 'aRowSkillData'=>$aRowSkillData]);	
		return $sHtml;
	}


    /**
     * update partner skill action
     * @param Request $request
     * @return false|string
     */
    public function supplierSkillUpdate(Request $request)
	{
		$response = SystemResponse::build(false,array('message'=>''));
		try {
			if (empty($request['skill_id'])) {
				$response = SystemResponse::build(false,array('message'=>'Please select skill'));
			}elseif (empty($request['experience'])) {
				$response = SystemResponse::build(false, array('message'=>'Please enter experience'));
            }elseif (!empty($request['experience']) && $request['experience'] < 1) {
                $response = SystemResponse::build(false,array('message'=>'Experience must be between 1 and 99'));
            }elseif (!empty($request['experience']) && $request['experience'] > 99) {
                $response = SystemResponse::build(false,array('message'=>'Experience must be between 1 and 99'));
			}elseif (empty($request['rate'])) {
				$response = SystemResponse::build(false, array('message'=>'Please enter rate'));
            }elseif (!empty($request['rate']) && $request['rate'] < 1) {
                $response = SystemResponse::build(false,array('message'=>'Hourly rate must be between 1 and 10000'));
            }elseif (!empty($request['rate']) && $request['rate'] > 10000) {
                $response = SystemResponse::build(false,array('message'=>'Hourly rate must be between 1 and 10000'));
			}elseif (isset($request['skill_id']) && !empty($request['skill_id'])) {
				$supplierSkill = SupplierSkill::find($request->id);						
				$supplierSkill['skill_id'] = $request['skill_id'];
				$supplierSkill['experience'] = $request['experience'];
				$supplierSkill['rate'] = $request['rate'];
				$supplierSkill['comment'] = $request['comment'];
				$res = $supplierSkill->save();
			}

			if ($res) {					
				$sHtml = $this->getSkillHtml(); // get html content after insert skills
				$response =  SystemResponse::build(true, array('message'=>'Skill updated successfully','sHtml'=>$sHtml));

			}

			return json_encode($response);
		}catch(Exception $e){
			$response = SystemResponse::build(false,array('message'=>$e->getMessage()));
			return json_encode($response);
			//return redirect()->back()->with('failed',"operation failed");
		}		
	}


    /**
     * store partner skills
     * @param Request $request
     * @return false|string
     */
    public function savePartnerSkills(Request $request)
	{
		$response = SystemResponse::build(false,array('message'=>''));

		try{
			if (empty($request['skill'])) {
				$response = SystemResponse::build(false,array('message'=>'Please select skill'));
			}elseif (empty($request['experience'])) {
				$response = SystemResponse::build(false,array('message'=>'Please enter experience'));
            }elseif (!empty($request['experience']) && $request['experience'] < 1) {
                $response = SystemResponse::build(false,array('message'=>'Experience must be between 1 and 100'));
            }elseif (!empty($request['experience']) && $request['experience'] > 100) {
                $response = SystemResponse::build(false,array('message'=>'Experience must be between 1 and 100'));
			}elseif (empty($request['rate'])) {
				$response = SystemResponse::build(false,array('message'=>'Please enter rate'));
            }elseif (!empty($request['rate']) && $request['rate'] < 1) {
                $response = SystemResponse::build(false,array('message'=>'Hourly rate must be between 1 and 99999'));
            }elseif (!empty($request['rate']) && $request['rate'] > 99999) {
                $response = SystemResponse::build(false,array('message'=>'Hourly rate must be between 1 and 99999'));
			}elseif (isset($request['skill']) && !empty($request['skill']) && count($request['skill']) > 0 ) {				
				$supplierId = $request->session()->get('partnerId');
				foreach ($request['skill'] as $key => $skillId) {
					$aRowSupplierSkillExists =  SupplierSkill::where(['skill_id'=> $skillId, 'supplier_id'=>$supplierId])->first();
	
					if ($aRowSupplierSkillExists ) {
						$supplierSkill = SupplierSkill::find($aRowSupplierSkillExists->id);						
						$supplierSkill['experience'] = $request['experience'];
						$supplierSkill['rate'] = $request['rate'];
						$supplierSkill['comment'] = $request['comment'];
						$res = $supplierSkill->save();
					}else {
						$supplierSkill = new SupplierSkill();
						$supplierSkill['skill_id'] = $skillId;
						$supplierSkill['supplier_id'] = $supplierId;
						$supplierSkill['experience'] = $request['experience'];
						$supplierSkill['rate'] = $request['rate'];
						$supplierSkill['comment'] = $request['comment'];
						$res = $supplierSkill->save();
					}
				}			

				if ($res) {					
					$sHtml = $this->getSkillHtml(); // get html content after insert skills
					$aRowSupplierData = SupplierSkill::where(['supplier_id'=>$supplierId])->orderBy('updated_at','DESC')->get();
					$response = SystemResponse::build(true, array('message'=>'Skill Inserted successfully','sHtml'=>$sHtml,'CurrentSkillId'=>$aRowSupplierData[0]->skill_id));
				}

			} 
			return json_encode($response);

		}catch(Exception $e){
			$response = SystemResponse::build(false, array('message'=>$e->getMessage()));

			return json_encode($response);
		}
	}

    /**
     * delete partner skills
     * @param Request $request
     * @return false|string
     */
    public function deleteSupplierSkill(Request $request)
	{
		$response = ['status'=>false,'message'=>''];
		try {
			if (isset($request->id) && !empty($request->id)) {
				$res = SupplierSkill::where(['id'=>$request->id])->delete();
			}

			if ($res) {					
				$sHtml = $this->getSkillHtml(); // get html content after insert skills
                $response = SystemResponse::build(true, array('message'=>'Skill deleted successfully','sHtml'=>$sHtml));
            }
			return json_encode($response);

		} catch (\Exception $e) {
			$response = SystemResponse::build(false,array('message'=>$e->getMessage()));
            Slack::send(new ExceptionCought($e));
			return json_encode($response);
		}
	}

    /**
     * after finish partner registration show thank you page
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function thankYou(Request $request)
	{
        $user = User::where(['id'=>$request->session()->get('userId')])->first();
        $password = $request->session()->get('password');
        if (!empty($user) && !empty($password)) {
     		$isEmailBounce = $user->company()->select('is_email_bounce')->first();
     		if (!$isEmailBounce['is_email_bounce']) {
     			Mail::send('emails.send_login_cred', ['user' => $user, 'password' => $password], function ($m) use ($user) {
                	$m->to($user->email, $user->name)->subject('Eternitech Partner Network - Your Login Credentials');
            	});
            	$emailBounced = CustomHelper::checkAndUpdateBounceFlagEmail($user->email, $user->company_id);
            }

            Slack::send(new LogJob(true, 'New Partner registered',
                array('channel' => BaseChannel::CRM, 'content' => 'Username: ' . $user->name), LogJob::CHANNEL_PARTNERS));

//            Mail::send('emails.send_admin_notification', ['user' => $user, 'password' => $password], function ($m) use ($user) {
//                $m->to(Config::get('constant.adminEmailAddress'), $user->name)->subject('Eternitech - New Partner Registered');
//            });

            return view('partner-registration.welcome');
        } else {
            return view('errors.error', [
                'title' => 'Session expired',
                'message' => 'Your session expired please try again',
            ]);
        }
	}

    /**
     * automatic login after partner registration done
     * @param Request $request
     * @return bool[]
     */
    public function partnerLogin(Request $request)
	{
	    $userId = $request->userID;
        if($request->session()->has('userId')){
            $userId = $request->session()->get('userId');
        }
	    if(Auth::user()){
            Auth::guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();
        }
		if($userId){
            Auth::loginUsingId($userId, true);
        }
		return ['status'=>true];
	}

    /**
     * display phone and email input form page
     *
     * @param Request $request
     * @param         $id
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function getOTPAccessCode(Request $request, $key)
    {
        $cacheJob = Cache::get($key);

        if ($cacheJob) {
            $channel = $cacheJob['channel'];
            return view('website.get-otp', [
                'channel' => $channel,
                'key' => $key
            ]);
        } else {
            return view('errors.error', [
                'title'   => 'Acccess token request expired',
                'message' => 'Access code request no longer in effect, try to be faster the next time.'
            ]);
        }
    }

    /**
     * store phone and email
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function setOTPAccessCode(Request $request, $key)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'code' => 'required',
        ]);

        if ($validator->passes()) {
            $cacheJob = Cache::get($key);

            if ($cacheJob && $request->code) {
                dispatch(new ScraperContinueJob($key, $request->code));
                return back()->with('flash.success','Access code submitted successfully');
            } else {
                return back()->with('flash.error','Access code request expired');
            }
        }
    }
}
