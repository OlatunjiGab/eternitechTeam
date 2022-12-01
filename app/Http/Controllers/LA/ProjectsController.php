<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Curl;
use App\Helpers\Message;
use App\Helpers\Quote;
use App\Helpers\ShortLink;
use App\Helpers\SystemResponse;
use App\Http\Controllers\Controller;
use App\Models\ColumnSetting;
use App\Models\Language;
use App\Models\ProjectMessage;
use App\Models\ProjectSkill;
use App\Models\TemplateMessage;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use URL;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Company;
use App\Models\PartnerAccess;
use Mail as Email;
use Nexmo;
use function Doctrine\Common\Cache\Psr6\get;
use App\Jobs\EmailJob;
use App\User;

class ProjectsController extends Controller
{
    public $show_client_name   = true;
    public $show_company_name  = true;
    public $show_company_phone = true;
    public $project_attention  = true;
    public $created_at         = true;
    public $source             = true;
    public $page_name          = true;
    public $menu_name          = true;
    public $affiliate          = true;
    public $show_action        = true;
    public $view_col           = 'name';
    public $view_col_status    = 'status';
    public $view_is_hourly     = 'is_hourly';
//    public $listing_cols       = [
//        'id',
//        'name',
//        'description',
//        'url',
//        'channel',
//        'skills',
//        'categories',
//        'project_budget',
//        'is_hourly',
//        'status',
//        'client_name',
//        'phone',
//        'email',
//        'project_attention',
//        'created_at',
//        'source',
//        'affiliate'
//    ];
    protected $columnDefs = array(
        'id' => array(
            'label'         => 'id',
            'isDynamic'     => false,
            'width'         => '5%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => true
        ),
        'name' => array(
            'label'         => 'Name',
            'isDynamic'     => false,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => true
        ),
        'description'       => array(
            'label'         => 'Description',
            'isDynamic'     => false,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'url'               => array(
            'label'         => 'Url',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'channel'           => array(
            'label'         => 'Channel',
            'isDynamic'     => true,
            'width'         => '5%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => true
        ),
        'skills'            => array(
            'label'         => 'Skills',
            'isDynamic'     => true,
            'width'         => '5%',
            'searchable'    => false,
            'defaultSearch' => false,
            'orderable'     => false
        ),
        'categories'     => array(
            'label'         => 'Categories',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'project_budget'    => array(
            'label'         => 'Project Budget',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'is_hourly'         => array(
            'label'         => 'Is Hourly',
            'isDynamic'     => true,
            'width'         => '5%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'status'            => array(
            'label'         => 'Status',
            'isDynamic'     => true,
            'width'         => '5%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'client_name'       => array(
            'label'         => 'Client Name',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'phone'             => array(
            'label'         => 'Client Phone',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'email'             => array(
            'label'         => 'Client Email',
            'isDynamic'     => true,
            'width'         => '5%',
            'searchable'    => true,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'project_attention' => array(
            'label'         => 'Project Attention',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => false,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'created_at'        => array(
            'label'         => 'Created',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => false,
            'defaultSearch' => false,
            'orderable'     => false
        ),
        'source'            => array(
            'label'         => 'Source',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => false,
            'defaultSearch' => true,
            'orderable'     => false
        ),
        'affiliate'         => array(
            'label'         => 'Affiliate',
            'isDynamic'     => true,
            'width'         => '10%',
            'searchable'    => false,
            'defaultSearch' => true,
            'orderable'     => false
        )
    );
    protected $columnDefaultSettings = [
        'url',
        'channel',
        'skills',
        'created_at',
    ];

//    public $recentListingCols = [
//        'id',
//        'name',
//        'description',
//        'url',
//        'channel',
//        'status',
//        'created'
//    ];

    public function __construct()
    {
        parent::__construct();
        // Field Access of Listing Columns
        if (\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                return $next($request);
            });
        } else {
            $this->middleware(function ($request, $next) {
                return $next($request);
            });
        }
        $this->datetime = \Carbon\Carbon::now()->todatetimeString();
    }

    /**
     * Display a listing of the Projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type = 'new')
    {
        $moduleName = 'Projects';
        $module = Module::get($moduleName);
        $viewOnly = !\Entrust::hasRole("SUPER_ADMIN");
        $statusList = Project::getStatuses();
        $channelList = BaseChannel::getAllChannels();
        $sourceList = Project::SOURCE_LIST_WITHOUT_WEBSITE;
        $user = Auth::user();
        $totalCount = Project::where('deleted_at', '=', null)->whereNull('projects.page_name')->count();

        if (Module::hasAccess($module->id)) {
            return View('la.projects.index', [
//                'show_client_name'   => $this->show_client_name,
//                'show_company_name'  => $this->show_company_name,
//                'show_company_phone' => $this->show_company_phone,
//                'project_attention'  => $this->project_attention,
//                'created_at'         => $this->created_at,
                'module'            => $moduleName,
                'channelList'       => $channelList,
                'skillList'         => Skill::getSkillList(),
//                'source'             => $this->source,
//                'affiliate'          => $this->affiliate,
                'sourceList'        => $sourceList,
                'statusList'        => $statusList,
                'type'               => $type,
//                'user'               => $user,
                'totalCount'        => $totalCount,
                'columnDefs'        => $this->columnDefs,
                'columnDefaultSettings' => $this->columnDefaultSettings,
                'viewOnly'          => $viewOnly
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Warm Project listing page show
     *
     * @param Request $request
     * @param string  $type
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function warmProjects(Request $request, $type = 'new')
    {
        $projectStatuses = Project::getStatuses();
        $channelList = BaseChannel::getAllChannels();

        $module = Module::get('Projects');

        if (Module::hasAccess($module->id)) {
            return View('la.projects.warm', [
                'show_client_name'   => $this->show_client_name,
                'show_company_name'  => $this->show_company_name,
                'show_company_phone' => $this->show_company_phone,
                'project_attention'  => $this->project_attention,
                'created_at'         => $this->created_at,
                'show_actions'       => $this->show_action,
                'listing_cols'       => array_keys($this->columnDefs),
                'module'             => $module,
                'channelList'        => $channelList,
                'statuses'           => $projectStatuses
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        try {

            $projectStatuses = Project::getStatuses();
            $channelList = BaseChannel::getAllChannels();

            $aRowCompany = Company::select('id', 'email')->where('email', '!=', '')->where('is_banned', '=', 0)->get();
            $aSkill = Skill::select('id', 'keywords', 'keyword')->get()->toArray();
            $currencyList = Quote::CURRENCY_LIST;
            $sourceList = Project::SOURCE_LIST;
            $user = \Auth::user();
            $companyEmail = '';
            $companyPhone = '';
            if (!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id) {
                $company = Company::select('email', 'phone')->where('id', $user->company_id)->first();
                if ($company && $company->email) {
                    $companyEmail = $company->email;
                }
                if ($company && $company->phone) {
                    $companyPhone = $company->phone;
                }
            }

            return view('la.projects.create',
                [
                    'aRowCompany'  => $aRowCompany,
                    'aSkill'       => $aSkill,
                    'statuses'     => $projectStatuses,
                    'currencyList' => $currencyList,
                    'companyEmail' => $companyEmail,
                    'sourceList'   => $sourceList,
                    'companyPhone' => $companyPhone,
                    'channelList'  => $channelList
                ]);
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));

            return back()->with('flash.error', $e->getMessage());
        }
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
        $data = $request->all();
        $validator = Validator::make($data, [
            //'email'          => 'email',
            'email'         => 'required_without:phone|email',
            'phone'         => 'required_without:email',
            'affiliateName' => 'required_if:affiliate,1',
            //'phone'          => 'required_if:affiliate,1|min:10|max:14',
            //'skill'          => 'required|array',
            //'experience'     => 'required|numeric',
            'name'          => 'required',
            'description'   => 'required',
            //'categories'     => 'required',
            //'currency'       => 'required',
            //'project_budget' => 'required',
            'is_hourly'     => 'required',
            //'url'            => 'active_url|unique:projects',
            'channel'       => 'required',
        ],
            [
                //'categories.required' => 'The Meta Keywords field is required.',
                'name.required'             => 'The Project Name field is required.',
                'description.required'      => 'The Project Description field is required.',
                'affiliateName.required_if' => 'The Name field is required.',
                'phone.required_if'         => 'The Phone field is required.',
            ]);

        if ($validator->passes()) {
            $companyExist = '';
            if (!empty($request['email'])) {
                $companyExist = Company::where(['email' => $request['email']])->first();
            }

            if (empty($companyExist)) {
                $companySaveData = [
                    'email'              => $request['email'],
                    'phone'              => $request['phone'],
                    'channel'            => $request['channel'],
                    'name'               => $request['affiliate'] ? $request['affiliateName'] : '',
                    'address'            => $request['affiliate'] ? $request['affiliateAddress'] : '',
                    'address2'           => $request['affiliate'] ? $request['affiliateAddress2'] : '',
                    'city'               => $request['affiliate'] ? $request['affiliateCity'] : '',
                    'state'              => $request['affiliate'] ? $request['affiliateState'] : '',
                    'country'            => $request['affiliate'] ? $request['affiliateCountry'] : '',
                    'zipcode'            => $request['affiliate'] ? $request['affiliateZipcode'] : '',
                    'fax'                => $request['affiliate'] ? $request['affiliateFax'] : '',
                    //'logo_url'   => $request['affiliate']?$request['affiliateLogo']:'',
                    'strategic'          => 0,
                    'is_banned'          => 0,
                    'agency_description' => $request['affiliate'] ? $request['affiliateDescription'] : '',
                    'agency_website'     => $request['affiliate'] ? $request['affiliateWebsite'] : '',
                    'language'           => 'en',
                    'updated_at'         => $this->datetime,
                    'created_at'         => $this->datetime
                ];

                $companyId = Company::insertGetId($companySaveData);
            } else {
                $companyId = $companyExist->id;
                $companyExist->phone = $request['phone'];
                $companyExist->save();
            }

            if ($companyId > 0) {

                $projectExist = \DB::table('projects')->join('project_companies', 'project_companies.project_id', '=',
                    'projects.id')->orWhere(['projects.name' => $request['name']])->orWhere(['description' => $request['description']])->select(['projects.id'])->first();
                $projectExist = '';

                if (empty($projectExist)) {
                    $user = Auth::user();
                    $partner_id = ($user->roles->first()->name == 'PARTNER') ? $user->id : 0;
                    $saveProjectData = [
                        'affiliate'      => $request['affiliate'] ? 1 : 0,
                        'url'            => $request['url'],
                        'categories'     => $request['categories'] ? : '',
                        'currency'       => $request['currency'] ? : '',
                        'project_budget' => $request['project_budget'] ? : '',
                        'is_hourly'      => $request['is_hourly'],
                        'name'           => $request['name'],
                        'experience'     => $request['experience'] ? : '',
                        'description'    => $request['description'],
                        'channel'        => $request['channel'],
                        'source'         => $request['source'],
                        'partner_id'     => $partner_id,
                        'updated_at'     => $this->datetime,
                        'created_at'     => $this->datetime
                    ];

                    $projectId = \DB::table('projects')->insertGetId($saveProjectData);

                    \DB::table('project_companies')->insert([
                        'project_id' => $projectId,
                        'company_id' => $companyId,
                        'updated_at' => $this->datetime,
                        'created_at' => $this->datetime
                    ]);
                } else {
                    $projectId = $projectExist->id;

                    return redirect()->back()->withErrors('errors',
                        'Project already exist')->withInput();
                }

                /*$user = Auth::user();
                if(!empty($user) && $user->roles->first()->name == 'PARTNER' && !empty($projectId)) {
                    $project = Project::find($projectId);
                    $message  = "<p><h4>Added one more new Lead</h4></p>";
                    $message .= "<strong>Name :</strong> $project->name <br>";
                    $message .= $project->description? "<strong>Description :</strong> $project->description <br>" : '';
                    $message .= $project->categories ? "<strong>Skills :</strong> $project->categories <br>" : "";
                    $message .= $project->project_budget? "<strong>Budget ($) :</strong> $project->project_budget <br>" : '';
                    $message .= $project->is_hourly ? "<strong>Hourly Based Project </strong><br>" : "<strong>Fixed Price Project</strong><br>";
                    $message .= $project->url? "<strong>Website :</strong> $project->url <br>" : '';
                    $message .= $project->created_at? "<strong>DateTime :</strong> $project->created_at " : '';

                    $partners = Supplier::select('partner_email')->where('company_id','!=',0)->where('company_id','!=',$companyId)->get();
                    $partnersIds = $partners->toArray();
                    foreach ($partnersIds as $key => $value){
                        $config = array(
                            'template' => 'send_message',
                            'subject' => 'New Lead Added in CRM',
                            'params' =>  ["msg"=>$message],
                            'to' => [$value['partner_email']]
                        );

                        $defaults = array_merge(array('sendAs'=>'html','template'=>'default','body'=>'No message','title'=>'Simple','subject'=>'Subject'),$config);

                        $body =  view('emails.'.$defaults['template'], $defaults['params']);
                        //$body =  new HtmlString(with(new CssToInlineStyles)->convert($body));
                        $body = html_entity_decode($body);

                        // add the basic header footer
                        $res = Email::send('emails.default', ['title' => $defaults['title'], 'body' => $body], function ($message) use($defaults,$body){
                            if(isset($defaults['from'])){
                                $message->from($defaults['from']);
                            } else {
                                $message->from(LAConfigs::getByKey('default_email'), 'Eternitech Web Professionals');
                            }
                            $message->to($defaults['to']);
                            $message->subject($defaults['subject']);
                            $message->setBody($body,'text/html');
                        });
                    }
                }*/
                if (!empty($projectId) && isset($request['skill']) && !empty($request['skill'])) {
                    (new \App\Models\ProjectSkill())->saveProjectSkills($projectId, $request['skill']);
                    $project = Project::find($projectId);
                    dispatch(new \App\Jobs\EmailJob($project, $request['skill']));
                }
            }

            return redirect()->route(config('laraadmin.adminRoute') . '.projects.index')->with('success',
                'Project has been saved successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        /*if(Module::hasAccess("Projects", "create")) {

            $rules = Module::validateRules("Projects", $request);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $insert_id = Module::insert("Projects", $request);

            return redirect()->route(config('laraadmin.adminRoute') . '.projects.index');

        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }*/
    }

    /**
     * Display the specified project.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (\Entrust::hasRole("PARTNER")) {
            if (!DB::table('lead_views')->where('lead_id', $id)->where('partner_id', \Auth::user()->id)->first()) {
                DB::table('lead_views')->insert(
                    ['lead_id' => $id, 'partner_id' => \Auth::user()->id]
                );
            }
        }
        $leadRecords = DB::table('lead_views')
            ->select('lead_id', 'partner_id')
            ->where('lead_id', '=', $id);
        $leadViews = $leadRecords->count();
        $ip = $request->getClientIp();
        $timezone = '';
        if ($ipInfo = file_get_contents('http://ip-api.com/json/' . $ip)) {
            $ipInfo = json_decode($ipInfo);
            $timezone = $ipInfo->timezone;
        }
        if (empty($timezone)) {
            $timezone = config('app.timezone');
        }
        if (Module::hasAccess("Projects", "view")) {
            $project = Project::find($id);
            $msgQuery = DB::table('project_messages')
                ->leftJoin('users', 'users.id', '=', 'project_messages.sender_id')
                ->where('project_id', $id)
                ->select(['project_messages.*', 'users.name as sender_name'])
                ->orderBy('project_messages.id', 'DESC');
            if (\Entrust::hasRole("PARTNER")) {
                $msgQuery->where('project_messages.sender_id', \Auth::user()->id);
            }
            $projectMessages = collect($msgQuery->get())->toArray();
            $skills = DB::table('skills')
                ->join('project_skills', 'project_skills.skill_id', '=', 'skills.id')
                ->where('project_skills.project_id', $id)
                ->select('skills.keyword')
                ->pluck('keyword')->toArray();

            $company = $project->company();
            if (\Auth::user()->role_user()->first()->role_id != 1) {
                if ($company->id != \Auth::user()->company_id) {
                    //return redirect()->back();
                }
            }

            $user = Auth::user();
            $hasAccess = true;
            if (!empty($user) && $user->roles->first()->name == 'PARTNER') {
                if ($project->source == Project::SOURCE_COMMUNITY || $project->source == Project::SOURCE_PARTNER) {
                    $hasAccess = true;
                } else {
                    $hasAccess = true;
                }
            }
            if (isset($project->id) && $hasAccess) {
                $templateSlugs = TemplateMessage::TEMPLATE_LIST;
                unset($templateSlugs[TemplateMessage::PLACE_BID_MESSAGE]);
                $templates = TemplateMessage::where('status', 1)->whereNull('partner_id')->where('language', '=',
                    $project->language)->whereNotIn('slug', $templateSlugs)->pluck('title', 'id');
                if (\Entrust::hasRole('PARTNER')) {
                    $partnerID = Auth::user()->supplier_id;
                    $templates = TemplateMessage::where('status', 1)->where('language', '=',
                        $project->language)->where('partner_id', '=', $partnerID)->whereNotIn('slug',
                        $templateSlugs)->pluck('title', 'id');
                }
                $status = 'new';
                switch ($project->status) {
                    case 1:
                        $status = "replied";
                        break;
                    case 2:
                        $status = 'negotiation';
                        break;
                    case 3:
                        $status = 'in_progress';
                        break;
                    case 4:
                        $status = 'done';
                        break;
                    case 5:
                        $status = 'archive';
                        break;
                    default:
                        $status = 'new';
                        break;
                }
                $module = Module::get('Projects');
                $module->row = $project;
                $companyModule = Module::get('Companies');
                $companyModule->row = $company;
                $shortLinkList = ShortLink::getShortLinkList($project->id, $project->url);
                $projectStatus = Project::getStatuses();

                //  exit;
                return view('la.projects.show', [
                    'templates'      => $templates,
                    'module'         => $module,
                    'view_col'       => $this->view_col,
                    'no_header'      => true,
                    'no_padding'     => "no-padding",
                    'skills'         => $skills,
                    'leadViews'      => $leadViews,
                    'companyModule'  => $companyModule,
                    'company'        => $company,
                    'aPMessage'      => $projectMessages,
                    'timezone'       => $timezone,
                    'project_status' => $status,
                    'shortLinkList'  => $shortLinkList,
                    'projectStatus'  => $projectStatus,
                    'user'           => $user,
                ])->with('project', $project);
            } else {
                return view('errors.404', [
                    'record_id'   => $id,
                    'record_name' => ucfirst("project"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projectStatuses = Project::getStatuses();
        $channelList = BaseChannel::getAllChannels();
        $languageList = Language::getLanguageList();
        if (Module::hasAccess("Projects", "edit")) {
            $project = Project::find($id);
            $user = Auth::user();
            $hasAccess = true;
            if (!empty($user) && $user->roles->first()->name == 'PARTNER') {
                if ($user->id != $project->partner_id) {
                    $hasAccess = false;
                }
            }
            if (isset($project->id) && $hasAccess) {
                $module = Module::get('Projects');

                $module->row = $project;

                $projectSkill = \DB::table('project_skills')->where('project_id',
                    $project->id)->pluck('skill_id')->toArray();

                $skills = \DB::table('skills')->where('deleted_at', null)->pluck('keyword', 'id')->toArray();
                $currencyList = Quote::CURRENCY_LIST;
                $sourceList = Project::SOURCE_LIST;
                $user = Auth::user();

                //echo "<pre>";print_r($skills);print_r($projectSkill);die;
                return view('la.projects.edit', [
                    'sourceList'    => $sourceList,
                    'currencyList'  => $currencyList,
                    'module'        => $module,
                    'view_col'      => $this->view_col,
                    'aSkill'        => $skills,
                    'projectSkills' => $projectSkill,
                    'channelList'   => $channelList,
                    'languageList'  => $languageList,
                    'statuses'      => $projectStatuses,
                    'deleteMessage' => $this->deleteMessage,
                    'user'          => $user
                ])->with('project', $project);
            } else {
                return view('errors.404', [
                    'record_id'   => $id,
                    'record_name' => ucfirst("project"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Module::hasAccess("Projects", "edit")) {

            $rules = Module::validateRules("Projects", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            $pData = $request->all();

            if (isset($pData['skill'])) {
                $skill = $pData['skill'];
                $projectSkill = ProjectSkill::where('project_id', $id)->pluck('skill_id', 'id')->toArray();
                $removeSkill = array_diff($projectSkill, $skill);
                $saveSkill = array_diff($skill, $projectSkill);

                if (!empty($removeSkill)) {
                    ProjectSkill::where('project_id', $id)->whereIn('skill_id', $removeSkill)->delete();
                }
                if (!empty($saveSkill)) {
                    $aSaveData = [];
                    $datetime = \Carbon\Carbon::now()->toDateTimeString();
                    foreach ($saveSkill as $key => $value) {
                        $aSaveData[] = [
                            'project_id' => $id,
                            'skill_id'   => $value,
                            'created_at' => $datetime,
                            'updated_at' => $datetime
                        ];
                    }
                    \DB::table('project_skills')->insert($aSaveData);
                }
            }
            $insert_id = Module::updateRow("Projects", $request, $id);
            if (isset($request->source) && $project = Project::where('id', $id)->first()) {
                $project->source = $request->source;
                if ($request->language) {
                    $project->language = $request->language;
                }
                if ($request->currency) {
                    $project->currency = $request->currency;
                }
                if ($request->experience) {
                    $project->experience = $request->experience;
                }
                if ($request->follow_up_date) {
                    $project->follow_up_date = Carbon::createFromFormat('d/m/Y',
                        $request->follow_up_date)->format('Y-m-d');
                }
                $project->save();
            }

            return redirect(config('laraadmin.adminRoute') . '/projects/' . $id)->with('success',
                'Project updated successfully...');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Module::hasAccess("Projects", "delete")) {
            Project::find($id)->delete();

            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.projects.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * lead listing Datatable Ajax fetch
     *
     * @param Request $request
     * @param null    $today
     * @param null    $date
     *
     * @return mixed
     */
    public function dtAjax(Request $request, $today = null, $date = null)
    {
        $viewOnly = !\Entrust::hasRole("SUPER_ADMIN");
        $fields = array_keys($this->columnDefs);

        $selectFields = [
            'projects.id',
            'projects.name',
            'projects.description',
            'projects.url',
            'projects.channel',
            \DB::raw('group_concat(project_skills.skill_id) as skills'),
            'projects.categories',
            'projects.project_budget',
            'projects.is_hourly',
            'projects.status',
            "companies.name as client_name",
            "companies.phone as phone",
            "companies.email as email",
            "projects.project_attention",
            "projects.created_at as created_at",
            'projects.source',
            "projects.affiliate",
            "project_companies.company_id as client_id",
            "projects.automated",
            "projects.partner_id",
            \DB::raw('group_concat(skills.keyword) as skill_keywords')
        ];
        $values = DB::table('projects')
            ->join("project_companies", "project_companies.project_id", "=", "projects.id")
            ->join("companies", "companies.id", "=", "project_companies.company_id")
            ->leftjoin("project_skills", "project_skills.project_id", "=", "projects.id")
            ->leftjoin("skills", "skills.id", "=", "project_skills.skill_id")
            ->select($selectFields)->whereNull('projects.deleted_at')
            ->whereNull('projects.page_name');

        //Filter apply on records
        foreach($request['columns'] as $item){
            if(!empty($item['search']['value']) && !$this->columnDefs[$item['name']]['defaultSearch']){
                $value = $item['search']['value'];
                switch ($item['name']) {
                    case 'name':
                    case 'description':
                    case 'url':
                    case 'categories':
                    case 'client_name':
                        $field = $item['name'] == 'name' ? 'projects.name' : ($item['name'] == 'description' ? 'projects.description' : ($item['name'] == 'url' ? 'projects.url' : ($item['name'] == 'categories' ? 'projects.categories' : 'companies.name')));
                        $values = $values->where($field,'LIKE','%'.$value.'%');
                        break;
                    case 'skills':
                        $values = $values->whereIn('project_skills.skill_id',explode(',', $value));
                        break;
                    case 'created_at':
                        $dateRange = explode("to", $value);
                        $fromDate = $dateRange[0];
                        $toDate = $dateRange[1];
                        $values = $values->whereDate("projects.created_at", ">=", $fromDate)
                            ->whereDate("projects.created_at", "<=", $toDate);
                        break;
                    default:
                        $field = $item['name'] == 'id' ? 'projects.id' : ($item['name'] == 'channel' ? 'projects.channel' : ($item['name'] == 'project_budget' ? 'projects.project_budget' : ($item['name'] == 'is_hourly' ? 'projects.is_hourly' : ($item['name'] == 'status' ? 'projects.status' : ($item['name'] == 'phone' ? 'companies.phone' : ($item['name'] == 'email' ? 'companies.email' : ($item['name'] == 'project_attention' ? 'projects.project_attention' : ($item['name'] == 'source' ? 'projects.source' : 'projects.affiliate'))))))));
                        $values = $values->where($field,$value);
                        break;
                }
            }
        }
        $user = \Auth::user();
        $user_role = $user->roles->first()->id;
        if (!empty($user) && $user_role == 2 && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Leads')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                $values = $values->where(function ($query) {
                    $query->where('projects.source', '=', Project::SOURCE_PARTNER)
                        ->orWhere([
                            ['projects.source', '=', Project::SOURCE_COMMUNITY],
                            ['projects.created_at', '<', Carbon::now()->subDay()],
                        ]);
                });
            } elseif ($route && $route->is_access == 1) {
                $values = $values->where(function ($query) {
                    $query->where('projects.source', '=', Project::SOURCE_PARTNER)
                        ->orWhere([
                            ['projects.source', '=', Project::SOURCE_COMMUNITY],
                            ['projects.created_at', '<', Carbon::now()->subDay()],
                        ]);
                });
            } elseif (!$route || ($route && !in_array($route->is_access, [2, 1, 0]))) {
                if ($user_role != 1 && $user->company_id != 0) {
                    $values = $values->where('project_companies.company_id', $user->company_id);
                }
            }
        } else {
            if ($user_role != 1 && $user->company_id != 0) {
                $values = $values->where('project_companies.company_id', $user->company_id);
            }
        }

        if ($today != null && $today != 'affiliate') {
            $searchDate = '';
            if (empty($request['columns'][14]['search']['value'])) {
                $searchDate = date('Y-m-d');
                if (!empty($date)) {
                    $searchDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
                }
            }
            if (in_array($today, [2, 3, 4, 5])) {
                $values->join('project_messages', 'projects.id', '=', 'project_messages.project_id');
                $userEvents = ProjectMessage::EVENT_TYPE_EMAIL_OPEN;
                if ($today == 2) {
                    $userEvents = [
                        ProjectMessage::EVENT_TYPE_SMS_SEND,
                        ProjectMessage::EVENT_TYPE_EMAIL_SEND,
                        ProjectMessage::EVENT_TYPE_BID_PLACE
                    ];
                } elseif ($today == 5) {
                    $userEvents = array_values(ProjectMessage::EVENT_TYPES_USERS_ACTIONS);
                }
                if (is_array($userEvents)) {
                    $values->whereIn('project_messages.event_type', $userEvents);
                } else {
                    $values->where('project_messages.event_type', "=", $userEvents);
                }
                $values->groupBy('projects.id');
                if (!empty($searchDate)) {
                    $values = $values->whereDate("project_messages.created_at", ">=",
                        $searchDate)->whereDate("project_messages.created_at", "<=", $searchDate);
                }
            } else {
                if (!empty($searchDate)) {
                    $values = $values->whereDate("projects.created_at", ">=",
                        $searchDate)->whereDate("projects.created_at", "<=", $searchDate);
                }
            }
        } elseif ($today == 'affiliate') {
            $values->where('projects.affiliate', "=", 1);
        }

        $values = $values->groupBy('projects.id')->orderBy('projects.id', 'DESC')->orderBy('companies.email', 'ASC');

        $out = Datatables::of($values)->make();
        $data = $out->getData();

        //$fields_popup = ModuleFields::getModuleFields('Projects');

        $partnerPopupClass = "listing-disable-feature-popup";
        if (Auth::user()->canAccess()) {
            $partnerPopupClass = "";
        }
        for ($i = 0; $i < count($data->data); $i++) {
            $projectIds = $data->data[$i][0];

            for ($j = 0; $j < count($fields); $j++) {
                $col = $fields[$j];
                /*if ($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    echo $data->data[$i][$j];die;
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }*/
                switch ($col) {
                    case 'id':
                        if ($viewOnly) {
                            $idString = "<div class='form-check'><input type='checkbox'  class='deleteRow' id='" . $projectIds . "' value='" . $projectIds . "'  /> <label class='form-check-label' for='" . $projectIds . "'> $projectIds </label></div>";
                        } else {
                            $idString = "<label class='form-check-label' for='" . $projectIds . "'> $projectIds </label>";
                        }
                        $data->data[$i][$j] = $idString;
                        break;
                    case 'url':
                        $data->data[$i][$j] = '<a href="' . $data->data[$i][$j] . '" target="_blank">' . $data->data[$i][$j] . '</a>';
                        break;
                    case 'description':
                        $data->data[$i][$j] = '<div style="height:100px;overflow-x: hidden;overflow-y: auto; text-align:justify; white-space: initial; word-break: break-all;">' . $data->data[$i][$j] . '</div>';
                        break;
                    case $this->view_col:
                        $communityLink = '';
                        if ($data->data[$i][15] == Project::SOURCE_COMMUNITY) {
                            $projectType = Project::SOURCE_LIST[$data->data[$i][15]];
                            //$communityLink = ShortLink::getCrmCommunityLeadLink($projectIds);
                            $communityLink = "<br>$projectType<br><span class='community-link' data-toggle='tooltip' data-content='copied.' onclick='copyCommunityLink(this)' title='Click to copy link' >" . ShortLink::getCrmCommunityLeadLink($projectIds) . "</span>";
                        }
                        $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $projectIds) . '">' . $data->data[$i][$j] . '</a>' . $communityLink;
                        break;
                    case $this->view_col_status:
                        $data->data[$i][$j] = Project::getStatusLabel($data->data[$i][$j]);
                        break;
                    case $this->view_is_hourly:
                        $data->data[$i][$j] = Project::IS_HOURLY_LIST[$data->data[$i][$j]];
                        break;
                    case 'skills':
                        $data->data[$i][$j] = $data->data[$i][20];
                        break;
                    case "client_name":
                        $data->data[$i][$j] = '<a class="' . $partnerPopupClass . '" href="' . url(config('laraadmin.adminRoute') . '/companies/' . $data->data[$i][17]) . '" target="_blank">' . $data->data[$i][$j] . '</a> ';
                        break;
                    case "source":
                        $communityLink = '';
                        if ($data->data[$i][$j] == Project::SOURCE_COMMUNITY) {
                            $communityLink = ShortLink::getCrmCommunityLeadLink($projectIds);
                            $communityLink = "<span class='community-link' onclick='copyCommunityLink(this)' title='Click to copy link' >$communityLink</span>";
                        }
                        $data->data[$i][$j] = Project::SOURCE_LIST[$data->data[$i][$j]] . " " . $communityLink;
                        break;
                    case "affiliate":
                        $data->data[$i][$j] = $data->data[$i][$j] ? "Yes" : "No";;
                        break;
                    default:
                        $data->data[$i][$j] = $data->data[$i][$j];
                        break;
                }
            }

            /*if ($this->show_client_name) {
                $data->data[$i][9] = '<a class="' . $partnerPopupClass . '" href="' . url(config('laraadmin.adminRoute') . '/companies/' . $data->data[$i][15]) . '" target="_blank">' . $data->data[$i][9] . '</a> ';
            }

            if ($this->show_company_phone) {
                $data->data[$i][10] = $data->data[$i][10];
            }
            if ($this->show_company_name) {
                $data->data[$i][11] = $data->data[$i][11];
            }

            if ($this->project_attention) {
                $data->data[$i][12] = $data->data[$i][12];
            }

            if ($this->created_at) {
                $data->data[$i][13] = $data->data[$i][13];
            }

            if ($this->source) {
                $communityLink = '';
                if ($data->data[$i][14] == Project::SOURCE_COMMUNITY) {
                    $communityLink = ShortLink::getCrmCommunityLeadLink($projectIds);
                    $communityLink = "<span class='community-link' onclick='copyCommunityLink(this)' title='Click to copy link' >$communityLink</span>";
                }
                $data->data[$i][14] = Project::SOURCE_LIST[$data->data[$i][14]] . " " . $communityLink;
            }
            if ($this->affiliate) {
                $data->data[$i][15] = $data->data[$i][18] ? "Yes" : "No";
            }*/

            if ($this->show_action) {
                $output = '';

                $output .= Project::getStatusLabel($sStatusVal) . "<br/>";

                if (Module::hasAccess("Projects", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $projectIds . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }

                if (Module::hasAccess("Projects", "delete")) {
                    $output .= Form::open([
                        'route'    => [
                            config('laraadmin.adminRoute') . '.projects.destroy',
                            $projectIds
                        ],
                        'method'   => 'delete',
                        'style'    => 'display:inline',
                        'onsubmit' => $this->deleteMessage
                    ]);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                if (!empty($user) && $user->roles->first()->name == 'PARTNER') {
                    if ($user->id != $data->data[$i][19]) {
                        $output = Project::getStatusLabel($sStatusVal) . "<br/>";
                    }
                }

                $data->data[$i][17] = (string)$output;
            }
        }
        $out->setData($data);

        return $out;
    }

    /**
     * Warm Projects Datatable Ajax fetch
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function warmProjectsajax(Request $request)
    {

        $fields = array_keys($this->columnDefs);
        //unset($fields[0]);
//        $fields[0] = 'projects.id';
//        $fields[1] = 'projects.name';
        $values = DB::table('projects')
            ->join("project_companies", "project_companies.project_id", "=", "projects.id")
            ->join("project_messages", "projects.id", "=", "project_messages.project_id")
            ->join("companies", "companies.id", "=", "project_companies.company_id")
            ->select('projects.id', 'projects.name', 'projects.description', 'projects.url', 'projects.channel',
                'projects.categories', 'projects.project_budget', 'projects.is_hourly', 'projects.status',
                "companies.name as client_name", "companies.phone as phone", "companies.email as email",
                "projects.project_attention",
                "projects.created_at as created_at", "project_companies.company_id as client_id")
            ->whereNull('projects.deleted_at');
        if (isset($request->all()['status']) && !empty($request->all()['status'])) {
            $values = $values->where('projects.status', '=', $request->all()['status']);
        }
        if (isset($request->all()['company_id'])) {
            $values = $values->where('project_companies.company_id', $request->all()['company_id']);
        }
        if (\Auth::user()->role_user()->first()->role_id != 1) {
            $values = $values->where('project_companies.company_id', \Auth::user()->company_id);
        }
        $values = $values->orderBy('project_messages.updated_at', 'DESC');

        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = ModuleFields::getModuleFields('Projects');

        for ($i = 0; $i < count($data->data); $i++) {

            for ($j = 0; $j < count($fields); $j++) {
                $col = $fields[$j];
                if ($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }

                if ($col == "id") {
                    $projectIds = $data->data[$i][0];
                    $data->data[$i][$j] = "<div class='form-check'><input type='checkbox'  class='deleteRow' id='" . $projectIds . "' value='" . $projectIds . "'  /> <label class='form-check-label' for='" . $projectIds . "'> $projectIds </label></div>";
                }

                if ($col == "url") {
                    $data->data[$i][$j] = '<a href="' . $data->data[$i][$j] . '" target="_blank">' . $data->data[$i][$j] . '</a>';
                }

                if ($col == "description") {
                    $data->data[$i][$j] = '<div style="height:100px;overflow-x: hidden;overflow-y: auto; text-align:justify; white-space: initial; word-break: break-all;">' . $data->data[$i][$j] . '</div>';
                }

                if ($col == $this->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $projectIds) . '">' . $data->data[$i][$j] . '</a>';
                }
                if ($col == $this->view_col_status) {
                    $sStatusVal = $data->data[$i][$j];
                    $data->data[$i][$j] = Project::getStatusLabel($data->data[$i][$j]);
                }
            }

            if ($this->show_client_name) {
                $data->data[$i][9] = '<a href="' . url(config('laraadmin.adminRoute') . '/companies/' . $data->data[$i][13]) . '" target="_blank">' . $data->data[$i][9] . '</a> ';
            }

            if ($this->show_company_phone) {
                $data->data[$i][10] = $data->data[$i][10];
            }

            if ($this->show_company_name) {
                $data->data[$i][11] = $data->data[$i][11];
            }

            if ($this->project_attention) {
                $data->data[$i][12] = $data->data[$i][12];
            }

            if ($this->created_at) {
                $data->data[$i][13] = $data->data[$i][13];
            }

            if ($this->source) {
                $data->data[$i][14] = $data->data[$i][14];
            }

            if ($this->show_action) {
                $output = '';

                $output .= Project::getStatusLabel($sStatusVal) . "<br/>";

                if (Module::hasAccess("Projects", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $projectIds . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }

                if (Module::hasAccess("Projects", "delete")) {
                    $output .= Form::open([
                        'route'    => [
                            config('laraadmin.adminRoute') . '.projects.destroy',
                            $projectIds
                        ],
                        'method'   => 'delete',
                        'style'    => 'display:inline',
                        'onsubmit' => $this->deleteMessage
                    ]);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }

                $data->data[$i][15] = (string)$output;
            }
        }
        $out->setData($data);

        return $out;
    }

    /**
     * send message from lead timeline page
     *
     * @param Request $request
     * @param         $projectId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(Request $request, $projectId)
    {
        try {
            if (!empty($projectId)) {
                $project = Project::findOrFail($projectId);
                $pData = $request->all();
                if (!empty($pData) && isset($pData['message'])) {
                    $user = Auth::user();
                    switch ($request->messageschannel) {
                        case ProjectMessage::CHANNEL_COMMENT:

                            if ($request->status) {
                                $project->status = $request->status;
                            }
                            if ($request->follow_up_date) {
                                $project->follow_up_date = Carbon::createFromFormat('d/m/Y',
                                    $request->follow_up_date)->format('Y-m-d');
                            }
                            $project->save();
                            $name = $user->name;
                            $message = "<b> $name </b> commented ($request->message)";
                            $project->addMessage($project->channel, $user, $message,
                                ProjectMessage::EVENT_TYPE_COMMENT);
                            \Session::flash('success', 'Message commented.');
                            break;
                        case strtolower(Message::CHANNEL_SMS):
                            $company = $project->company();
                            if (!empty($request->message) && !empty($company->phone)) {

                                $message['message'] = $request->message;
                                $response = Message::sendSMS($project, $message);
                                if ($response) {
                                    $message = "<b> SMS send event </b> Message:  $request->message";
                                    $project->addMessage($project->channel, $user, $message,
                                        ProjectMessage::EVENT_TYPE_SMS_SEND);
                                    \Session::flash('success', 'SMS send...');
                                }
                            } else {
                                \Session::flash('error', 'please write message');
                            }
                            break;
                        case strtolower(Message::CHANNEL_EMAIL):
                            $body = $pData['message'];
                            $body .= " Ref::$project->id!";
                            $body = nl2br($body);

                            $message = [
                                'subject'     => $pData['subject'] ?? '',
                                'message'     => $body,
                                'type'        => ProjectMessage::EVENT_TYPE_EMAIL_SEND,
                                'isAutomated' => false
                            ];

                            $template = TemplateMessage::whereId($pData['template_id'])->first();
                            $success = $project->sendMessage($message, Message::CHANNEL_EMAIL, $user, $template);

                            if ($success) {
                                if ($project->status == Project::STATUS_PENDING) {
                                    $project->status = Project::STATUS_BID_PLACED;
                                    $project->save();
                                }
                                \Session::flash('success', 'Email has been sent.');
                            } else {
                                \Session::flash('error', 'There was a problem with the submit');
                            }

                            break;
                        case Message::CHANNEL_SCRAPER:
                        case ProjectMessage::CHANNEL_BID:

                            $project = Project::with([
                                'projectCompany.company',
                                'projectSkills'
                            ])->findOrFail($projectId);
                            $is_signature = $request->is_signature == 'on' ? true : false;
                            $template = TemplateMessage::whereId($request->template_id)->first();
                            $isBidPlace = $project->bid(true, $is_signature, $template->slug);
                            if (!$isBidPlace) {
                                \Session::flash('error_message',
                                    "There is a problem in your request , please try again");
                            } else {
                                \Session::flash('success_message', " bid placed successfully ");
                            }
                            break;
                        default:
                            throw new \Exception("Couldn't find message type " . $request->messageschannel);
                    }
                } else {
                    throw new \Exception("There is a problem Message is empty");
                }
            } else {
                throw new \Exception("There is a problem");
            }
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
            \Session::flash('error_message', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * multiple lead delete action
     *
     * @param $ids required
     *
     * @return resonse in array
     */
    public function MultipleProjectsDeleted(Request $req)
    {
        try {
            $response = SystemResponse::build(false);
            if (isset($req['data_ids']) && !empty($req['data_ids'])) {
                $ids = explode(",", $req['data_ids']);
                $data = Project::whereIn('id', $ids)->delete();
                if ($data) {
                    $response = SystemResponse::build(true);
                }
                $response = SystemResponse::build(true);
            }
            \Session::flash('success', 'Selected records deleted...');

            return json_encode($response);
        } catch (\Exception $e) {
            \Session::flash('error_message', $e->getMessage());
        }
    }

    /**
     * place bid from lead timeline page
     *
     * @param Request $request
     * @param         $projectId
     * @param string  $bidType
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeBid(Request $request, $projectId, $bidType = 'manual')
    {
        try {
            $project = Project::with(['projectCompany.company', 'projectSkills'])
                /*->where([
                    'projects.status' => Project::STATUS_PENDING
                ])*/
                ->where(['projects.id' => $projectId])
                ->where('projects.url', '!=', '')
                ->first();

            if (!empty($project)) {
                $isBidPlace = $project->bid(true);
                if (!$isBidPlace) {
                    \Session::flash('error_message', "There is a problem in your request , please try again");
                    //throw new \Exception("There is a problem in your request , please try again", 1);
                } else {
                    \Session::flash('success_message', " bid placed successfully ");
                }
            }
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
            \Session::flash('error_message', $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * place bid with selected template from lead timeline page
     *
     * @param Request $request
     * @param         $projectId
     * @param string  $bidType
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeIndividualBid(Request $request, $projectId, $bidType = 'manual')
    {
        if (!empty($request->template_id)) {
            $is_signature = $request->is_signature == 'on' ? true : false;
            try {
                $project = Project::with(['projectCompany.company', 'projectSkills'])
                    ->where(['projects.id' => $projectId])
                    ->where('projects.url', '!=', '')
                    ->first();

                if (!empty($project)) {
                    $template = TemplateMessage::whereId($request->template_id)->first();
                    $isBidPlace = $project->bid(true, $is_signature, $template->slug);
                    if (!$isBidPlace) {
                        \Session::flash('error_message', "There is a problem in your request , please try again");
                        //throw new \Exception("There is a problem in your request , please try again", 1);
                    } else {
                        \Session::flash('success_message', " bid placed successfully ");
                    }
                }
            } catch (\Exception $e) {
                Slack::send(new ExceptionCought($e));
                \Session::flash('error_message', $e->getMessage());
            }
        } else {
            \Session::flash('error_message', " please select template");

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * for get phone number while selecting email on lead create page
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhone(Request $request)
    {
        $companyExist = Company::where(['email' => $request['email']])->first();
        if ($companyExist > 0) {
            $data['phone'] = $companyExist->phone;
            $response = [
                'status'  => true,
                'message' => "get phone successfully",
                'data'    => $data,
            ];
        } else {
            $data['phone'] = "";
            $response = [
                'status'  => false,
                'message' => "Data not found",
                'data'    => $data
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * @param Request $request
     * @param         $projectId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function toggleAutomation(Request $request, $projectId)
    {
        $project = Project::where('id', '=', $projectId)->first();

        if (!$project) {
            throw new \Exception("project not found");
        }
        $project->automated = !$project->automated;
        $project->save();

        $response = [
            'status'    => true,
            'automated' => $project->automated,
        ];

        return response()->json($response, 200);
    }

    /**
     * recent lead page show
     *
     * @param Request $request
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function recentLeads(Request $request)
    {
        $projectStatuses = Project::getStatuses();
        $channelList = BaseChannel::getAllChannels();
        $module = Module::get('Projects');

        if (Module::hasAccess($module->id)) {
            return View('la.projects.recent-lead', [
                'show_client_name'   => $this->show_client_name,
                'show_company_name'  => $this->show_company_name,
                'show_company_phone' => $this->show_company_phone,
                'project_attention'  => $this->project_attention,
                'created_at'         => $this->created_at,
                'show_actions'       => $this->show_action,
                'listing_cols'       => array_keys($this->columnDefs),
                'module'             => $module,
                'channelList'        => $channelList,
                'statuses'           => $projectStatuses
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * Recent Lead Datatable Ajax fetch
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function recentLeadDtAjax(Request $request)
    {
        $userEvents = array_values(ProjectMessage::EVENT_TYPES_USERS_ACTIONS);
        $values = \DB::table("project_messages")
            ->join('projects', 'project_messages.project_id', '=', 'projects.id')
            ->select('projects.id as id', 'projects.name as name',
                'projects.description', 'projects.url', 'projects.channel', 'projects.status',
                'projects.created_at as created')
            ->whereNull('projects.deleted_at')
            ->where('project_id', '!=', 0)
            ->where('is_system_created', '=', 0)
            ->whereIn('event_type', $userEvents)
            ->groupBy('project_id')
            ->orderBy('project_messages.created_at', 'DESC');

        if (isset($request['columns'][6]['search']['value']) && !empty($request['columns'][6]['search']['value'])) {
            $dateRange = explode("to", $request['columns'][6]['search']['value']);
            $fromDate = $dateRange[0];
            $toDate = $dateRange[1];
            $values = $values->whereDate("projects.created_at", ">=", $fromDate)
                ->whereDate("projects.created_at", "<=", $toDate);
        }

        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = ModuleFields::getModuleFields('Projects');

        for ($i = 0; $i < count($data->data); $i++) {

            for ($j = 0; $j < count($this->recentListingCols); $j++) {
                $col = $this->recentListingCols[$j];
                if ($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if ($col == "id") {
                    $projectIds = $data->data[$i][0];
                    $data->data[$i][$j] = $projectIds;
                }
                if ($col == $this->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $projectIds) . '">' . $data->data[$i][$j] . '</a>';
                }

                if ($col == "url") {
                    $data->data[$i][$j] = '<a href="' . $data->data[$i][$j] . '" target="_blank">' . $data->data[$i][$j] . '</a>';
                }
                if ($col == "description") {
                    $data->data[$i][$j] = '<div style="height:100px;overflow-x: hidden;overflow-y: auto; text-align:justify; white-space: initial; word-break: break-all;">' . $data->data[$i][$j] . '</div>';
                }
                if ($col == $this->view_col_status) {
                    $sStatusVal = $data->data[$i][$j];
                    $data->data[$i][$j] = Project::getStatusLabel($data->data[$i][$j]);
                }
            }
        }
        $out->setData($data);

        return $out;
    }

    /**
     * recent lead event listing page show
     *
     * @param Request $request
     * @param int     $eventType
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function recentLeadEvent(Request $request, $eventType = 0)
    {
        $userEventList = ProjectMessage::EVENT_TYPES_USERS_ACTIONS_NAMES;
        $userEvents = ProjectMessage::EVENT_TYPES_USERS_ACTIONS;
        unset($userEvents[ProjectMessage::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK]);
        $userEvents = array_values($userEvents);
        if ($eventType) {
            $userEvents = [$eventType];
        }

        $values = DB::table("project_messages")
            ->join('projects', 'project_messages.project_id', '=', 'projects.id')
            ->select(DB::raw("project_messages.id as id, project_id,
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
            ->orderBy('project_messages.id', 'DESC');
        if ($eventType) {
            //$values->where('event_type','=', $eventType);
        }
        $recentLeadEvents = $values->get()->unique('project_id');
        $module = Module::get('Projects');

        if (Module::hasAccess($module->id)) {
            return View('la.projects.recent-lead-event', [
                'recentLeadEvents' => $recentLeadEvents,
                'userEventList'    => $userEventList,
                'eventType'        => $eventType,
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * for read unread message from header section list
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function isRead(Request $request, $id)
    {
        $projectID = '';

        if ($project = ProjectMessage::find($id)) {
            $projectID = $project->project_id;

            $project->is_read = 1;
            $project->save();
        }

        return redirect(config('laraadmin.adminRoute') . '/projects/' . $projectID);
    }

    public function columnSetting(Request $request)
    {
        $columnSettings = ColumnSetting::select('id', 'key', 'value')->get()->toArray();

        return View('la.projects.column-setting', ['columnSettings' => $columnSettings]);
    }

    public function columnSettingUpdate(Request $request)
    {
        //dd($request->get('aData'));
        $response = [
            'status' => false,
        ];
        if ($request->get('aData') && !empty($request->get('aData'))) {
            $checkedIds = [];
            $uncheckedIds = [];
            foreach ($request->get('aData') as $id => $val) {
                if ($val == 1) {
                    $checkedIds[] = $id;
                } else {
                    $uncheckedIds[] = $id;
                }
            }
            if(!empty($checkedIds)){
                if(ColumnSetting::whereIn('key',$checkedIds)->update(['value'=>1])){
                    $response['status'] = true;
                }
            }
            if(!empty($uncheckedIds)){
                if(ColumnSetting::whereIn('key',$uncheckedIds)->update(['value'=>0])){
                    $response['status'] = true;
                }
            }
        }

        /*if (isset($request->id) && isset($request->value)) {
            if ($columnSetting = ColumnSetting::find($request->id)) {
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
        }*/

        return response()->json($response, 200);
    }

    /**
     * Display a listing of the menu wise leads for different pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function menuName(Request $request, $menu = 'new')
    {
        $projectStatuses = Project::getStatuses();
        $sourceList = Project::SOURCE_LIST;
        $columnSettings = ColumnSetting::getColumnSettingList();
        $user = Auth::user();
        $module = Module::get('Projects');

        $menuList = Project::MENU_LIST;
        $source = array_search($menu, $menuList);
        $totalCount = Project::where('deleted_at', '=', null)->where('menu_name', $source)->count();

        if (Module::hasAccess($module->id)) {
            return View('la.projects.page-name', [
                'show_client_name'   => $this->show_client_name,
                'show_company_name'  => $this->show_company_name,
                'show_company_phone' => $this->show_company_phone,
                'created_at'         => $this->created_at,
                'listing_cols'       => array_keys($this->columnDefs),
                'module'             => $module,
                'sourceList'         => $sourceList,
                'source'             => $this->source,
                'page_name'          => $this->page_name,
                'menu_name'          => $this->menu_name,
                'sourceList'         => $sourceList,
                'statuses'           => $projectStatuses,
                //'page'              => $page,
                'menu'               => $menu,
                'columnSettings'     => $columnSettings,
                'user'               => $user,
                'totalCount'         => $totalCount,
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }

    /**
     * lead page-name data listing Datatable Ajax fetch
     *
     * @param Request $request
     * @param null    $menuName
     *
     * @return mixed
     */
    public function dtLeadAjax(Request $request, $menuName = 'Homepage')
    {
        $fields = array_keys($this->columnDefs);
//        $fields[0] = 'projects.id';
//        $fields[1] = 'projects.name';

        $menuList = Project::MENU_LIST;
        $menu = array_search($menuName, $menuList);

        $values = DB::table('projects')
            ->join("project_companies", "project_companies.project_id", "=", "projects.id")
            ->join("companies", "companies.id", "=", "project_companies.company_id")
            ->select('projects.id', 'projects.name', 'projects.description',
                "companies.name as client_name", "companies.phone as phone", "companies.email as email",
                "projects.created_at as created_at", 'projects.source', 'projects.page_name',
                "project_companies.company_id as client_id")
            ->whereNull('projects.deleted_at')
            ->where('projects.menu_name', $menu);

        $user = \Auth::user();
        if (!empty($user) && $user->roles->first()->id == 2 && $user->company_id != 0) {
            $route = PartnerAccess::where('route', 'Leads')->where('user_id', $user->id)->first();
            if ($route && $route->is_access == 0) {
                $values = $values->where(function ($query) {
                    $query->where('projects.source', '=', Project::SOURCE_PARTNER)
                        ->orWhere([
                            ['projects.source', '=', Project::SOURCE_COMMUNITY],
                            ['projects.created_at', '<', Carbon::now()->subDay()],
                        ]);
                }
                );
            } elseif ($route && $route->is_access == 1) {
                $values = $values->where(function ($query) {
                    $query->where('projects.source', '=', Project::SOURCE_PARTNER)
                        ->orWhere([
                            ['projects.source', '=', Project::SOURCE_COMMUNITY],
                            ['projects.created_at', '<', Carbon::now()->subDay()],
                        ]);
                }
                );
            } elseif ($route && $route->is_access == 2) {

            } else {
                if (\Auth::user()->role_user()->first()->role_id != 1) {
                    $values = $values->where('project_companies.company_id', \Auth::user()->company_id);
                }
            }
        } else {
            if (\Auth::user()->role_user()->first()->role_id != 1) {
                $values = $values->where('project_companies.company_id', \Auth::user()->company_id);
            }
        }

        if (isset($request['columns'][7]['search']['value']) && !empty($request['columns'][7]['search']['value'])) {
            $searchSource = $request['columns'][7]['search']['value'];
            $values = $values->where("projects.source", "=", $searchSource);
        }

        $values = $values->orderBy('projects.id', 'DESC')->orderBy('companies.email', 'ASC');

        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = ModuleFields::getModuleFields('Projects');

        $partnerPopupClass = "listing-disable-feature-popup";
        if (Auth::user()->canAccess()) {
            $partnerPopupClass = "";
        }
        for ($i = 0; $i < count($data->data); $i++) {
            $projectIds = $data->data[$i][0];

            for ($j = 0; $j < count($fields); $j++) {
                if ($col == "id" || $col == 'name' || $col == 'description') {
                    $col = $fields[$j];
                    if ($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                        $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                    }

                    if ($col == "id") {
                        $projectIds = $data->data[$i][0];
                        if (\Entrust::hasRole("SUPER_ADMIN")) {
                            $idString = "<div class='form-check'><input type='checkbox'  class='deleteRow' id='" . $projectIds . "' value='" . $projectIds . "'  /> <label class='form-check-label' for='" . $projectIds . "'> $projectIds </label></div>";
                        } else {
                            $idString = "<label class='form-check-label' for='" . $projectIds . "'> $projectIds </label>";
                        }
                        $data->data[$i][$j] = $idString;
                    }

                    if ($col == "description") {
                        $data->data[$i][$j] = '<div style="height:100px;overflow-x: hidden;overflow-y: auto; text-align:justify; white-space: initial; word-break: break-all;">' . $data->data[$i][$j] . '</div>';
                    }

                    if ($col == $this->view_col) {
                        $communityLink = '';
                        if ($data->data[$i][14] == Project::SOURCE_COMMUNITY) {
                            $projectType = Project::SOURCE_LIST[$data->data[$i][14]];
                            $communityLink = ShortLink::getCrmCommunityLeadLink($projectIds);
                            $communityLink = "<br>$projectType<br><span class='community-link' data-toggle='tooltip' data-content='copied.' onclick='copyCommunityLink(this)' title='Click to copy link' >$communityLink</span>";
                        }
                        $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/projects/' . $projectIds) . '">' . $data->data[$i][$j] . '</a>' . $communityLink;
                    }
                }
            }

            if ($this->show_client_name) {
                $data->data[$i][3] = '<a class="' . $partnerPopupClass . '" href="' . url(config('laraadmin.adminRoute') . '/companies/' . $data->data[$i][9]) . '" target="_blank">' . $data->data[$i][3] . '</a> ';
            }

            if ($this->show_company_phone) {
                $data->data[$i][4] = $data->data[$i][4];
            }
            if ($this->source) {
                $communityLink = '';
                if ($data->data[$i][14] == Project::SOURCE_COMMUNITY) {
                    $communityLink = ShortLink::getCrmCommunityLeadLink($projectIds);
                    $communityLink = "<span class='community-link' onclick='copyCommunityLink(this)' title='Click to copy link' >$communityLink</span>";
                }
                $data->data[$i][7] = Project::SOURCE_LIST[$data->data[$i][7]] . " " . $communityLink;
            }
            if ($this->page_name) {
                $data->data[$i][8] = $data->data[$i][8];
            }
        }
        $out->setData($data);

        return $out;
    }

}