<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Channels\BaseChannel;
use App\Helpers\Curl;
use App\Helpers\Message;
use App\Helpers\Quote;
use App\Http\Controllers\Controller;
use App\Models\ColumnSetting;
use App\Models\Language;
use App\Models\TemplateMessage;
use App\Notifications\ExceptionCought;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use SendGrid\Mail\From;
use SendGrid\Mail\Mail;
use SendGrid\Mail\OpenTracking;
use SendGrid\Mail\TrackingSettings;
use URL;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use App\Models\Skill;
use Mail as Email;
use Nexmo;
use function Doctrine\Common\Cache\Psr6\get;
use App\Jobs\EmailJob;
use App\User;
use App\Models\ProjectTranslated;
use App\Models\Project;
use Illuminate\Support\Str;

class LeadsTrainingController extends Controller
{
    public $created_at        = true;
    public $show_action       = true;
    public $view_col          = 'name';
    public $view_col_status   = 'status';
    public $listing_cols      = [
        'id',
        'name',
        'description',
        'categories',
        'channel',
        'competition',
        'project_type',
        'remote',
        'provider_type',
        'provider_experience',
        'qualification',
        'project_length',
        'project_state',
        'project_urgency',
        'budget',
        'client_knowlegeable',
        'client_experience_with_dev',
        'industry',
        'is_client_it_company',
        'client_company_size',
        'is_trained',
        'is_it_related'
    ];
    public $recentListingCols      = [
        'id',
        'name',
        'description',
        'categories',
        'channel',
        'status',
        'created'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the Projects Translate.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $projectType = projectTranslated::PROJECT_TYPE;
        $providerType = projectTranslated::PROVIDER_TYPE;
        $providerExperience = projectTranslated::PROVIDER_EXPERIENCE;
        $qualification = projectTranslated::QUALIFICATION;
        $projectState = projectTranslated::PROJECT_STATE;
        $trained = ProjectTranslated::where('is_trained',1)->count();
        $untrained = ProjectTranslated::where('is_trained',0)->count();

        return View('la.project-translate.index', [
            'created_at'         => $this->created_at,
            'show_actions'       => $this->show_action,
            'listing_cols'       => $this->listing_cols,
            'user'               => $user,
            'service'            => $service,
            'projectType'        => $projectType,
            'providerType'       => $providerType,
            'providerExperience' => $providerExperience,
            'qualification'      => $qualification,
            'projectState'       => $projectState,
            'trained'            => $trained,
            'untrained'          => $untrained
        ]);
    }
    
    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created project in database.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified project translate.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        
    }

    /**
     * Show the form for editing the specified project translate.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projectTranslated = ProjectTranslated::findOrFail($id);
        $skillList  = Skill::getSkillList();
        $selectedSkills = explode(',',$projectTranslated->skills);
        $user = Auth::user();
        $service = projectTranslated::SERVICE;
        $selectedServices = explode(',',$projectTranslated->service);
        $projectType = projectTranslated::PROJECT_TYPE;
        $providerType = projectTranslated::PROVIDER_TYPE;
        $providerExperience = projectTranslated::PROVIDER_EXPERIENCE;
        $qualification = projectTranslated::QUALIFICATION;
        $projectState = projectTranslated::PROJECT_STATE;
        $getNextLead = DB::table('projects')->where('id', $id)->first();
    
        return view('la.project-translate.edit',['skillList' => $skillList, 'projectTranslated' => $projectTranslated, 'selectedSkills' => $selectedSkills, 'user' => $user, 'service' => $service, 'selectedServices' => $selectedServices, 'projectType' => $projectType, 'providerType' => $providerType, 'providerExperience' => $providerExperience, 'qualification' => $qualification, 'projectState' => $projectState, 'getNextLead' => $getNextLead]);
    }

    /**
     * Update the specified project translate in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
            
        if(!empty($data['skills'])){
            $data['skills'] = implode(',',$data['skills']);
        }
        if(!empty($data['service'])){
            $data['service'] = implode(',',$data['service']);
        }
        $projectTranslated = ProjectTranslated::where('id','=',$id)->first();
        $projectTranslated->update($data);

        $nextId = ProjectTranslated::where('id','>',$id)->min('id');

        return redirect(config('laraadmin.adminRoute') . '/project-translate/' . $nextId . '/edit')->with('success', 'Project updated successfully...');
    }

    /**
     * Remove the specified project translate from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    /**
     * lead listing Datatable Ajax fetch
     * @param Request $request
     * @param null $today
     * @param null $date
     * @return mixed
     */
    public function dtAjax(Request $request)
    {
        //$columns = array_flip($this->listing_cols);
        //$columns = array_flip($columns);
        $fields = $this->listing_cols;
       
        $values = DB::table('projects_translated')
                ->select('projects_translated.id', 'projects_translated.name', 'projects_translated.description', 'projects_translated.categories', 'projects_translated.channel', 'projects_translated.competition', 'projects_translated.project_type', "projects_translated.remote", 'projects_translated.provider_type', "projects_translated.provider_experience", "projects_translated.qualification", "projects_translated.project_length", "projects_translated.project_state", "projects_translated.project_urgency", "projects_translated.budget", "projects_translated.client_knowlegeable", "projects_translated.client_experience_with_dev", "projects_translated.industry", "projects_translated.is_client_it_company", "projects_translated.client_company_size", "projects_translated.is_trained", "projects_translated.is_it_related")
                ->whereNull('projects_translated.deleted_at');
                        
        if (isset($request['columns'][20]['search']['value'])) {
            $searchIsTrained = (int)$request['columns'][20]['search']['value'];
            $values = $values->where("projects_translated.is_trained", "=", $searchIsTrained);
        }
        if (isset($request->all()['is_it_related']) && !empty($request->all()['is_it_related'])) {
            $values = $values->where('projects.is_it_related', '=', $request->all()['is_it_related']);
        }
        
        //$values = $values->orderByRaw('projects_translated.is_trained ASC, projects_translated.is_manual ASC');
        $values = $values->orderBy('projects_translated.is_trained', 'ASC')->orderBy('projects_translated.is_manual', 'ASC');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        foreach ($data->data as $k=>$val) {
            $id = $val[0];
            $name = $val[1];
            //$description = Str::words($val[2], 7, '...').'<a href="javascript:void(0);" id='.$id.' class="show_more_desc">Show More</a>';
            $description = Str::limit($val[2], 60).'<a href="javascript:void(0);" id='.$id.' class="show_more_desc">Show More</a>';

            $categories = $val[3];
            $channel = $val[4];
            $competition = $val[5];
            $projectType = $val[6];
            $remote = $val[7];
            $providerType = $val[8];
            $providerExperience = $val[9];
            $qualification = $val[10];
            $projectLength = $val[11];
            $projectState = $val[12];
            $projectUrgency = $val[13];
            $budget = $val[14];
            $clientKnowlegeable = $val[15];
            $clientExperienceWithDev = $val[16];
            $industry = $val[17];
            $isClientItCompany = $val[18];
            $clientCompanySize = $val[19];
            $isTrained = ($val[20] == 1) ? 'Yes' : 'No';
            $isITRelated = ($val[21] == 1) ? 'Yes' : 'No';
            $data->data[$k] = [$id,$name,$description,$categories,$channel,$competition,$projectType,$remote,$providerType,$providerExperience,$qualification,$projectLength,$projectState,$projectUrgency,$budget,$clientKnowlegeable,$clientExperienceWithDev,$industry,$isClientItCompany,$clientCompanySize,$isTrained,$isITRelated];
            
            $output = '';
            $disabled = '';
            if($val[20]==1){
                $disabled = 'disabled';
            }
            $output .= '<a href="'.route(config('laraadmin.adminRoute') . '.project-translate.edit',$val[0]).'" class="btn btn-warning btn-xs" '.$disabled.' style="display:inline;padding:2px 5px 3px 5px;" title="Leads Training Edit"><i class="fa fa-edit"></i></a>';
            $output .= '<a href="'.route(config('laraadmin.adminRoute') . '.projects.edit',$val[0]).'" class="btn btn-warning btn-xs" style="margin-top: 5px;" title="Lead Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            $output .= Form::close();
            $data->data[$k][] = (string)$output;
        }
        $out->setData($data);
        return $out;
    }

    public function getDescription(Request $request)
    {
        $id = $request->id;
        $projectDescription = projectTranslated::select('description')->where('id',$id)->first();
        
        return $projectDescription['description'] ? $projectDescription['description'] : '';
    }

    public function columnSetting(Request $request) {
        $columnSettings = ColumnSetting::select('id','key','value')->get()->toArray();
        return View('la.projects.column-setting',['columnSettings'=>$columnSettings]);
    }

    public function columnSettingUpdate(Request $request) {
        if(isset($request->id) && isset($request->value)){
            if($columnSetting = ColumnSetting::find($request->id)) {
                $columnSetting->value = $request->value;
                $columnSetting->save();
            }
            $response = [
                'status' => true,
            ];
        } else {
            $response = [
                'status' => false,
            ];
        }

        return response()->json($response, 200);
    }

}