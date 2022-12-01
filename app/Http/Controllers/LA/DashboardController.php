<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\CustomHelper;
use App\Helpers\Message;
use App\Helpers\ShortLink;
use App\Http\Controllers\Controller;
use App\Models\InterestedPartner;
use App\Models\Project;
use App\Models\ProjectCompany;
use App\Models\ProjectMessage;
use App\Models\ProjectTranslated;
use App\Notifications\LogJob;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LAConfigs;
use SendGrid;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Skill;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $userEvents = array_values(ProjectMessage::EVENT_TYPES_USERS_ACTIONS);
        $ip = $request->getClientIp();
        $timezone = '';
        if($ipInfo = file_get_contents('http://ip-api.com/json/' . $ip)) {
            $ipInfo = json_decode($ipInfo);
            $timezone = $ipInfo->timezone;
        }
        if(empty($timezone)){
            $timezone = config('app.timezone');
        }
        $data['timezone'] = $timezone;
        $user = \Auth::user();
		
        if($user->roles->first()->name == 'SUPER_ADMIN') {
        $apiKey = env('SENDGRID_API_KEY');
        $sg = new SendGrid($apiKey);
        $response = $sg->client->stats()->get('',['aggregated_by'=>'day','start_date'=> date('Y-m-d'),'end_date'=>date('Y-m-d')]);
        if($response->statusCode() == 200) {
            $responseData = json_decode($response->body(),true);
            //$data['totalSend'] = $responseData[0]['stats'][0]['metrics']['delivered']?:0;
            $data['totalOpen'] = $responseData[0]['stats'][0]['metrics']['unique_opens']?:0;
        } else {
            $data['totalOpen'] = 0;
        }
        
        $data['aRowTotalTodayProjects'] = Project::whereDate('created_at', "=",\Carbon\Carbon::today())->where('deleted_at', '=', null)->whereNull('page_name')->count();

        $data['aRowTotalTodayOutMessage'] = ProjectMessage::whereIn('event_type',[ProjectMessage::EVENT_TYPE_SMS_SEND,ProjectMessage::EVENT_TYPE_EMAIL_SEND,ProjectMessage::EVENT_TYPE_BID_PLACE])->whereDate('created_at', "=",\Carbon\Carbon::today())->count();
        $data['aRowTotalTodayRepliesMessage'] = ProjectMessage::where('event_type',"=",ProjectMessage::EVENT_TYPE_EMAIL_REPLY)->whereDate('created_at', "=",\Carbon\Carbon::today())->count();
        $data['aRowTotalProjects'] = Project::where('deleted_at', '=', null)->count();
        $data['aRowTotalProjectsrecents'] = Project::orderBy('created_at','desc')->where('deleted_at', '=', null)->take(20)->get();

        $totalEngaged  = \DB::table("project_messages")
            ->join('projects','project_messages.project_id','=','projects.id')
            ->select('project_messages.project_id')
            ->whereNull('projects.deleted_at')
            ->where('project_id', '!=', 0)
            ->whereDate('project_messages.created_at','=',\Carbon\Carbon::today())
            ->where('is_system_created', '=', 0)
            ->whereIn('event_type', $userEvents)
            ->groupBy('project_id')
            ->get();
        $data['aRowTotalEngaged'] = count($totalEngaged);

        $sourceArr = [Project::SOURCE_DOWNLOAD_CV_LEAD,Project::SOURCE_HIRE_DEVELOPER,Project::SOURCE_BUILD_YOUR_TEAM,Project::SOURCE_GET_A_QUOTE,Project::SOURCE_MEETING,Project::SOURCE_CONTACT,Project::SOURCE_CHECKOUT,Project::SOURCE_ONLINE_TOOLS];
        $data['latestLeadEvents'] = \DB::table("project_messages")
            ->join('projects','project_messages.project_id','=','projects.id')
            ->select(\DB::raw("project_messages.id as id, project_id,
            projects.name as project_name,project_messages.message as event_message,
            project_messages.event_type as event_type,
            project_messages.ip_address as ip_address,
            project_messages.browser_name as browser_name,
            project_messages.os_name as os_name,
            project_messages.city as city,
            project_messages.country as country,
            project_messages.browser_version as browser_version,
            project_messages.os_version as os_version,
            project_messages.device_type as device_type,
            project_messages.country_code as country_code,
            project_messages.updated_at as event_datetime"))
            ->where('project_id', '!=', 0)
            ->where('is_system_created', '=', 0)
            ->whereIn('event_type', $userEvents)
            ->whereNotIn('projects.source', $sourceArr)
            ->orderBy('project_messages.id', 'DESC')
            ->get()
            ->unique('project_id')
            ->take(20);

        $data['followupLeads'] = Project::with('todoProjectMessages')
            ->whereHas('todoProjectMessages', function($q){
                $q->where('event_type','=', ProjectMessage::EVENT_TYPE_COMMENT);
            })
            ->select('id', 'name','follow_up_date')
            ->where('status','=',Project::STATUS_FOLLOW_UP)
            ->whereNotNull('follow_up_date')
            ->whereDate('follow_up_date','<=',Carbon::now())
            ->orderBy('follow_up_date', 'DESC')
            ->take(20)
            ->get();

        $lastSevenDatesArray = CustomHelper::getLastNDates(7);
        $aChannel = config('constant.channel.options');
        $revenueSaleChartData = [];
        //$lineSaleChartData = [];
        ini_set('max_execution_time', 300);
        foreach ($lastSevenDatesArray as $key => $date) {
            $revenueSaleChartData[$key]['y'] = $date;
            $revenueSaleChartData[$key]['totalProject'] = Project::whereDate('created_at', "=",$date)->where('deleted_at', '=', null)->count();
            $revenueSaleChartData[$key]['totalSent'] = ProjectMessage::where('sender_id',"!=","")->whereDate('created_at', "=",$date)->count();
            $revenueSaleChartData[$key]['totalReply'] = ProjectMessage::where('sender_id',"=","")->whereDate('created_at', "=",$date)->count();

            /*$lineSaleChartData[$key]['y'] = $date;
            foreach ($aChannel as $keyName => $channel) {
                $lineSaleChartData[$key][$keyName] = Project::whereDate('created_at', "=",$date)->where('deleted_at', '=', null)->where('channel','=',$keyName)->count();
            }*/
        }
        $data['interestedPartners'] = InterestedPartner::with('user')->whereDate('created_at', "=",Carbon::today())->groupBy('user_id')->take(20)->get();
        } else {
            $companyProjectIDS = ProjectCompany::select('project_id')->where('company_id','=',$user->company_id)->get();
            $companyProjectIDSArray = [];
            foreach ($companyProjectIDS->toArray() as $key => $value) {
                $companyProjectIDSArray[] = $value['project_id'];
            }
            //$data['aRowTotalTodayProjects'] = Project::whereDate('created_at', "=",\Carbon\Carbon::today())->where('deleted_at', '=', null)->whereIn('id',array_values($companyProjectIDSArray))->count();
            $data['aRowTotalTodayProjects'] = Project::whereDate('created_at', "=",\Carbon\Carbon::today())->where('deleted_at', '=', null)->where(function ($query) {
                $query->where('source', '=', Project::SOURCE_PARTNER)
                    ->orWhere([
                        ['source', '=', Project::SOURCE_COMMUNITY],
                        ['created_at', '<', Carbon::now()->subDay()],
                    ]);
            })->count();
            $data['aRowTotalTodayOutMessage'] = ProjectMessage::whereIn('event_type',[ProjectMessage::EVENT_TYPE_SMS_SEND,ProjectMessage::EVENT_TYPE_EMAIL_SEND,ProjectMessage::EVENT_TYPE_BID_PLACE])->whereDate('created_at', "=",\Carbon\Carbon::today())->whereIn('project_id',array_values($companyProjectIDSArray))->count();
            $data['aRowTotalTodayRepliesMessage'] = ProjectMessage::where('event_type',"=",ProjectMessage::EVENT_TYPE_EMAIL_REPLY)->whereDate('created_at', "=",\Carbon\Carbon::today())->whereIn('project_id',array_values($companyProjectIDSArray))->count();
            //$data['aRowTotalProjects'] = Project::where('deleted_at', '=', null)->whereIn('id',array_values($companyProjectIDSArray))->count();
            $data['aRowTotalProjects'] = Project::where('deleted_at', '=', null)->where(function ($query) {
                $query->where('source', '=', Project::SOURCE_PARTNER)
                    ->orWhere([
                        ['source', '=', Project::SOURCE_COMMUNITY],
                        ['created_at', '<', Carbon::now()->subDay()],
                    ]);
            })->count();
            //$data['aRowTotalProjectsrecents'] = Project::orderBy('created_at','desc')->where('deleted_at', '=', null)->take(20)->whereIn('id',array_values($companyProjectIDSArray))->where('partner_id',$user->id)->orWhere('partner_id','!=', 0)->get();
            $data['aRowTotalProjectsrecents'] = Project::orderBy('created_at','desc')->where('deleted_at', '=', null)->take(20)->where(function ($query) {
                $query->where('source', '=', Project::SOURCE_PARTNER)
                    ->orWhere([
                        ['source', '=', Project::SOURCE_COMMUNITY],
                        ['created_at', '<', Carbon::now()->subDay()],
                    ]);
            })->where('partner_id',$user->id)->orWhere('partner_id','!=', 0)->get();

            //$lastSevenDatesArray = CustomHelper::getLastNDates(7);
            $aChannel = config('constant.channel.options');
            //$revenueSaleChartData = [];
            //$lineSaleChartData = [];
            /*ini_set('max_execution_time', 300);
            foreach ($lastSevenDatesArray as $key => $date) {
                $revenueSaleChartData[$key]['y'] = $date;
                $revenueSaleChartData[$key]['totalProject'] = Project::whereDate('created_at', "=",$date)->where('deleted_at', '=', null)->whereIn('id',array_values($companyProjectIDSArray))->count();
                $revenueSaleChartData[$key]['totalSent'] = ProjectMessage::where('sender_id',"!=","")->whereDate('created_at', "=",$date)->whereIn('project_id',array_values($companyProjectIDSArray))->count();
                $revenueSaleChartData[$key]['totalReply'] = ProjectMessage::where('sender_id',"=","")->whereDate('created_at', "=",$date)->whereIn('project_id',array_values($companyProjectIDSArray))->count();

                $lineSaleChartData[$key]['y'] = $date;
                foreach ($aChannel as $keyName => $channel) {
                    $lineSaleChartData[$key][$keyName] = Project::whereDate('created_at', "=",$date)->where('deleted_at', '=', null)->where('channel','=',$keyName)->whereIn('id',array_values($companyProjectIDSArray))->count();
                }
            }*/
        }
        $data['revenueSaleChartData'] = $revenueSaleChartData;
        //$data['lineSaleChartData'] = $lineSaleChartData;
        $data['aChannel'] = $aChannel;
        if($user->roles->first()->name == 'SUPER_ADMIN') {
            return view('la.dashboard',['data'=>$data, 'user'=>$user]);
        }

        return view('la.partner-dashboard',['data'=>$data, 'user'=>$user]);
    }
	
	/**
     * Show the application dashboard for partners.
     *
     * @return Response
     */
	public function partnersIndex(Request $request)
    {
        $ip = $request->getClientIp();
        $timezone = '';
        if($ipInfo = file_get_contents('http://ip-api.com/json/' . $ip)) {
            $ipInfo = json_decode($ipInfo);
            $timezone = $ipInfo->timezone;
        }
        if(empty($timezone)){
            $timezone = config('app.timezone');
        }
        $data['timezone'] = $timezone;
        $user = \Auth::user();
		
        
		$companyProjectIDS = ProjectCompany::select('project_id')->where('company_id','=',$user->company_id)->get();
		$companyProjectIDSArray = [];
		foreach ($companyProjectIDS->toArray() as $key => $value) {
			$companyProjectIDSArray[] = $value['project_id'];
		}
		//$data['aRowTotalTodayProjects'] = Project::whereDate('created_at', "=",\Carbon\Carbon::today())->where('deleted_at', '=', null)->whereIn('id',array_values($companyProjectIDSArray))->count();
		$data['aRowTotalTodayProjects'] = Project::whereDate('created_at', "=",\Carbon\Carbon::today())->where('deleted_at', '=', null)->where(function ($query) {
			$query->where('source', '=', Project::SOURCE_PARTNER)
				->orWhere([
					['source', '=', Project::SOURCE_COMMUNITY],
					['created_at', '<', Carbon::now()->subDay()],
				]);
		})->count();
		$data['aRowTotalTodayOutMessage'] = ProjectMessage::whereIn('event_type',[ProjectMessage::EVENT_TYPE_SMS_SEND,ProjectMessage::EVENT_TYPE_EMAIL_SEND,ProjectMessage::EVENT_TYPE_BID_PLACE])->whereDate('created_at', "=",\Carbon\Carbon::today())->whereIn('project_id',array_values($companyProjectIDSArray))->count();
		$data['aRowTotalTodayRepliesMessage'] = ProjectMessage::where('event_type',"=",ProjectMessage::EVENT_TYPE_EMAIL_REPLY)->whereDate('created_at', "=",\Carbon\Carbon::today())->whereIn('project_id',array_values($companyProjectIDSArray))->count();
		//$data['aRowTotalProjects'] = Project::where('deleted_at', '=', null)->whereIn('id',array_values($companyProjectIDSArray))->count();
		$data['aRowTotalProjects'] = Project::where('deleted_at', '=', null)->where(function ($query) {
			$query->where('source', '=', Project::SOURCE_PARTNER)
				->orWhere([
					['source', '=', Project::SOURCE_COMMUNITY],
					['created_at', '<', Carbon::now()->subDay()],
				]);
		})->count();
		//$data['aRowTotalProjectsrecents'] = Project::orderBy('created_at','desc')->where('deleted_at', '=', null)->take(20)->whereIn('id',array_values($companyProjectIDSArray))->where('partner_id',$user->id)->orWhere('partner_id','!=', 0)->get();
		$data['aRowTotalProjectsrecents'] = Project::orderBy('created_at','desc')->where('deleted_at', '=', null)->take(20)->where(function ($query) {
			$query->where('source', '=', Project::SOURCE_PARTNER)
				->orWhere([
					['source', '=', Project::SOURCE_COMMUNITY],
					['created_at', '<', Carbon::now()->subDay()],
				]);
		})->where('partner_id',$user->id)->orWhere('partner_id','!=', 0)->get();

		//$lastSevenDatesArray = CustomHelper::getLastNDates(7);
		$aChannel = config('constant.channel.options');
		//$revenueSaleChartData = [];
		//$lineSaleChartData = [];
		/*ini_set('max_execution_time', 300);
		foreach ($lastSevenDatesArray as $key => $date) {
			$revenueSaleChartData[$key]['y'] = $date;
			$revenueSaleChartData[$key]['totalProject'] = Project::whereDate('created_at', "=",$date)->where('deleted_at', '=', null)->whereIn('id',array_values($companyProjectIDSArray))->count();
			$revenueSaleChartData[$key]['totalSent'] = ProjectMessage::where('sender_id',"!=","")->whereDate('created_at', "=",$date)->whereIn('project_id',array_values($companyProjectIDSArray))->count();
			$revenueSaleChartData[$key]['totalReply'] = ProjectMessage::where('sender_id',"=","")->whereDate('created_at', "=",$date)->whereIn('project_id',array_values($companyProjectIDSArray))->count();

			$lineSaleChartData[$key]['y'] = $date;
			foreach ($aChannel as $keyName => $channel) {
				$lineSaleChartData[$key][$keyName] = Project::whereDate('created_at', "=",$date)->where('deleted_at', '=', null)->where('channel','=',$keyName)->whereIn('id',array_values($companyProjectIDSArray))->count();
			}
		}*/
        
        $data['revenueSaleChartData'] = $revenueSaleChartData;
        //$data['lineSaleChartData'] = $lineSaleChartData;
        $data['aChannel'] = $aChannel;
        

        return view('la.partner-dashboard',['data'=>$data, 'user'=>$user]);
    }

    /**
     * filter count data based on date selection from dashboard.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountByDate(Request $request) {
        if(isset($request->searchDate) && !empty($request->searchDate)){
            $searchDate = Carbon::createFromFormat('d/m/Y',$request->searchDate)->format('Y-m-d');
                    
            $data['totalLead'] = Project::whereDate('created_at', "=",$searchDate)->where('deleted_at', '=', null)->whereNull('projects.page_name')->count();
            $data['totalOutMessage'] = ProjectMessage::whereIn('event_type',[ProjectMessage::EVENT_TYPE_SMS_SEND,ProjectMessage::EVENT_TYPE_EMAIL_SEND,ProjectMessage::EVENT_TYPE_BID_PLACE])->whereDate('created_at', "=",$searchDate)->count();
            $data['totalRepliesMessage'] = ProjectMessage::where('event_type',"=",ProjectMessage::EVENT_TYPE_EMAIL_REPLY)->whereDate('created_at', "=",$searchDate)->count();

            $apiKey = env('SENDGRID_API_KEY');
            $sg = new SendGrid($apiKey);
            $response = $sg->client->stats()->get('',['aggregated_by'=>'day','start_date'=> $searchDate,'end_date'=>$searchDate]);
            if($response->statusCode() == 200) {
                $responseData = json_decode($response->body(),true);
                $data['totalEmailOpen'] = $responseData[0]['stats'][0]['metrics']['unique_opens']?:0;
            } else {
                $data['totalEmailOpen'] = 0;
            }

            $totalEngaged  = \DB::table("project_messages")
                ->join('projects','project_messages.project_id','=','projects.id')
                ->select('project_messages.project_id')
                ->whereNull('projects.deleted_at')
                ->where('project_id', '!=', 0)
                ->whereDate('project_messages.created_at','=',$searchDate)
                ->where('is_system_created', '=', 0)
                ->whereIn('event_type', array_values(ProjectMessage::EVENT_TYPES_USERS_ACTIONS))
                ->groupBy('project_id')
                ->get();
            $data['totalEngaged'] = count($totalEngaged);

            $response = [
                'status' => true,
                'message' => "Data Fetch Successfully",
                'data' => $data,
            ];
        } else {
            $response = [
                'status' => false,
                'message' => "No Data Found",
                'data' => [''],
            ];
        }

        return response()->json($response, 200);
    }

    public function sendInterestedEmail(Request $request) {
        if(isset($request->featureName) && !empty($request->featureName)){
            $partner = Auth::user();
            $interestedPartner = InterestedPartner::where('user_id','=',$partner->id)->where('feature_name','=',$request->featureName)->exists();
            if(!$interestedPartner) {
                $interestedPartner = new InterestedPartner();
                $interestedPartner->feature_name = $request->featureName;
                $interestedPartner->user_id = $partner->id;
                $interestedPartner->save();
                $params = [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'email' => $partner->email,
                    'featureName' => $request->featureName,
                ];
//                $config = array(
//                    'template' => 'interested_mail',
//                    'subject' => 'Partner wants to upgrade the account',
//                    'params' => $params,
//                    'from' => [LAConfigs::getByKey('default_email')],
//                    'to' => [Config::get('constant.adminEmailAddress')]
//                );
//                Message::sendCustomEmail($config);

                Slack::send(new LogJob(true, 'Partner Premium request',
                    array('channel' => BaseChannel::CRM, 'content' => print_r($params, true)), LogJob::CHANNEL_PARTNERS));

                \Session::flash("success", "Your request to become a Premium partner has been registered you'll be contacted soon.");
                $response = [
                    'status' => true,
                ];
            }
            else{
                \Session::flash("success", "Your details have already been submitted");
                $response = [
                    'status' => true,
                ];
            }
        } else {
            $response = [
                'status' => false,
            ];
        }
        return response()->json($response, 200);
    }

    public function interestedPartners(Request $request) {
        $user = \Auth::user();
        $interestedPartners = InterestedPartner::with('user')->get();
        return view('la.interested-partners',['interestedPartners'=>$interestedPartners, 'user'=>$user,]);
    }

    public function premiumPlanInfo(Request $request) {
        return view('la.premium-plan-info');
    }

    public function translateLeads(Request $request)
    {
        set_time_limit(9999);
        ini_set('memory_limit', '-1');
        //export to CSV
        $listData = ProjectTranslated::orderBy('projects_translated.is_trained', 'ASC')->orderBy('projects_translated.is_manual', 'ASC')->get()->toArray();
        
        $list = [];
        foreach ($listData as $key=>$value) {
            $list[$key] = $value;
            $skillIDs = array_map('intval', explode(',', $value['skills']));
            $skillList  = Skill::whereIn('id', $skillIDs)->pluck('keyword')->toArray();
            $list[$key]['skills'] = implode(',', $skillList);
            $service = projectTranslated::SERVICE;
            $serviceValue = "";
            foreach (explode(',',$value['service']) as $skey=>$svalue){
                if(!empty($serviceValue)){
                    $serviceValue = $serviceValue.','.projectTranslated::SERVICE[$svalue];
                }else{
                    $serviceValue = projectTranslated::SERVICE[$svalue];
                }
            }
            $list[$key]['service'] = $serviceValue;
            $list[$key]['project_type'] = $value['project_type'];
            $list[$key]['provider_type'] = $value['provider_type'];
            $list[$key]['provider_experience'] = $value['provider_experience'];
            $list[$key]['qualification'] = $value['qualification'];
            $list[$key]['project_state'] = $value['project_state'];
            $list[$key]['project_link'] = route(config('laraadmin.adminRoute') . '.project-translate.edit',$value['id']);

            $list[$key]['is_trained'] = $value['is_trained'] ? 'Yes' : 'No';
            $list[$key]['is_manual'] = $value['is_manual'] ? 'Yes' : 'No';
            $list[$key]['is_it_related'] = $value['is_it_related'] ? 'Yes' : 'No';
        }
        $fileName = "projects_translated.csv";
        $headers = [
            'Cache-Control'       => "must-revalidate, post-check=0, pre-check=0",
            'Content-type'        => "text/csv",
            'Content-Disposition' => "attachment; filename=$fileName",
            'Expires'             => "0",
            'Pragma'              => "public",
        ];
        
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));
                
        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function mysqlExportLeadsTranslate(Request $request)
    {
        //ENTER THE RELEVANT INFO BELOW
        $mysqlHostName      = env('DB_HOST');
        $mysqlUserName      = env('DB_USERNAME');
        $mysqlPassword      = env('DB_PASSWORD');
        $DbName             = env('DB_DATABASE');
        $backup_name        = "mybackup.sql";
        $tables             = array("projects_translated"); //here your tables...

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();

        $output = '';
        foreach($tables as $table)
        {
             $show_table_query = "SHOW CREATE TABLE " . $table . "";
             $statement = $connect->prepare($show_table_query);
             $statement->execute();
             $show_table_result = $statement->fetchAll();

             foreach($show_table_result as $show_table_row)
             {
              $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
             }
             $select_query = "SELECT * FROM " . $table . "";
             $statement = $connect->prepare($select_query);
             $statement->execute();
             $total_row = $statement->rowCount();

             for($count=0; $count<$total_row; $count++)
             {
              $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
              $table_column_array = array_keys($single_result);
              $table_value_array = array_values($single_result);
              $output .= "\nINSERT INTO $table (";
              $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
              $table_value_array = str_replace("'", "", $table_value_array);
              $output .= "'" . implode("','", $table_value_array) . "');\n";
             }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);
    }

}