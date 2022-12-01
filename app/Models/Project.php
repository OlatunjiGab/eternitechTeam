<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Curl;

use App\Helpers\Message;
use App\Helpers\Quote;
use App\Helpers\ShortLink;
use App\Helpers\SystemResponse;
use App\Helpers\VisitorDetails;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use SoftDeletes;

    const STATUS_PENDING     = 0;
    const STATUS_BID_PLACED  = 1;
    const STATUS_NEGOTIATION = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_DONE        = 4;
    const STATUS_ARCHIVED    = 5;
    const STATUS_FOLLOW_UP   = 6;
    const STATUS_IS_VIEWED   = 7;
    const STATUS_ENGAGED     = 8;
    const STATUS_NOT_RELEVANT= 9;

    const IS_HOURLY_FIXED = 0;
    const IS_HOURLY = 1;
    const IS_HOURLY_LIST = [
        self::IS_HOURLY_FIXED => 'Fixed Price Project',
        self::IS_HOURLY => 'Hourly Based Project',
    ];

    const SOURCE_ADMIN = 0;
    const SOURCE_AUTOMATED = 1;
    const SOURCE_PARTNER = 2;
    const SOURCE_COMMUNITY = 3;
    const SOURCE_DOWNLOAD_CV_LEAD = 4;
    const SOURCE_GET_A_QUOTE = 5;
    const SOURCE_BUILD_YOUR_TEAM = 6;
    const SOURCE_HIRE_DEVELOPER = 7;
    const SOURCE_MEETING = 8;
    const SOURCE_CONTACT = 9;
    const SOURCE_CHECKOUT = 10;
    const SOURCE_ONLINE_TOOLS = 11;
    const SOURCE_LIST = [
        self::SOURCE_ADMIN => 'Admin',
        self::SOURCE_AUTOMATED => 'Automated',
        self::SOURCE_PARTNER => 'Partner',
        self::SOURCE_COMMUNITY => 'Community',
        self::SOURCE_DOWNLOAD_CV_LEAD => 'CV Lead',
        self::SOURCE_HIRE_DEVELOPER => 'Hire Developer Lead',
        self::SOURCE_BUILD_YOUR_TEAM => 'Build Your Team Lead',
        self::SOURCE_GET_A_QUOTE => 'Get Quote Lead',
        self::SOURCE_MEETING => 'Meeting Form Lead',
        self::SOURCE_CONTACT => 'Contact Form Lead',
        self::SOURCE_CHECKOUT => 'Checkout Form Lead',
        self::SOURCE_ONLINE_TOOLS => 'Online Tools Lead',
    ];
    const SOURCE_LIST_WITHOUT_WEBSITE = [
        self::SOURCE_ADMIN => 'Admin',
        self::SOURCE_AUTOMATED => 'Automated',
        self::SOURCE_PARTNER => 'Partner',
        self::SOURCE_COMMUNITY => 'Community',
    ];

    /*const PAGE_HOMEPAGE = 1;
    const PAGE_ABOUTUS = 2;
    const PAGE_SERVICES = 3;
    const PAGE_HIRE_DEVELOPERS = 4;
    const PAGE_PORTFOLIO = 5;
    const PAGE_ONLINE_TOOLS = 6;
    const PAGE_PARTNERS = 7;
    const PAGE_TECHNOLOGIES = 8;
    const PAGE_BLOG = 9;
    const PAGE_CONTACT_US = 10;
    const PAGE_LIST = [
        self::PAGE_HOMEPAGE => 'Homepage',
        self::PAGE_ABOUTUS => 'About Us',
        self::PAGE_SERVICES => 'Services',
        self::PAGE_HIRE_DEVELOPERS => 'Hire Developers',
        self::PAGE_PORTFOLIO => 'Portfolio',
        self::PAGE_ONLINE_TOOLS => 'Online Tools',
        self::PAGE_PARTNERS => 'Partners',
        self::PAGE_TECHNOLOGIES => 'Technologies',
        self::PAGE_BLOG => 'Blog',
        self::PAGE_CONTACT_US => 'Contact Us',
    ];*/

    const MENU_HOMEPAGE = 1;
    const MENU_ABOUTUS = 2;
    const MENU_SERVICES = 3;
    const MENU_HIRE_DEVELOPERS = 4;
    const MENU_PORTFOLIO = 5;
    const MENU_ONLINE_TOOLS = 6;
    const MENU_PARTNERS = 7;
    const MENU_TECHNOLOGIES = 8;
    const MENU_BLOG = 9;
    const MENU_CONTACT_US = 10;
    const MENU_LIST = [
        self::MENU_HOMEPAGE => 'Homepage',
        self::MENU_ABOUTUS => 'About Us',
        self::MENU_SERVICES => 'Services',
        self::MENU_HIRE_DEVELOPERS => 'Hire Developers',
        self::MENU_PORTFOLIO => 'Portfolio',
        self::MENU_ONLINE_TOOLS => 'Online Tools',
        self::MENU_PARTNERS => 'Partners',
        self::MENU_TECHNOLOGIES => 'Technologies',
        self::MENU_BLOG => 'Blog',
        self::MENU_CONTACT_US => 'Contact Us',
    ];

    protected $table = 'projects';

    protected $hidden = [

    ];

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function projectCompany()
    {
        return $this->hasOne('App\Models\ProjectCompany');
    }

    public function projectSkills()
    {
        return $this->hasMany('App\Models\ProjectSkill');
    }

    /**
     * @return null
     */
    public function company()
    {
        $projectCompany = $this->projectCompany()->first();

        if ($projectCompany) {
            return $projectCompany->company()->first();
        }

        return null;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
    /**
     * @param      $parseData
     * @param bool $attemptBid
     *
     * @return array
     */
    public static function createNewProject($parseData, $attemptBid = true)
    {
        $response = SystemResponse::build(false);

        if (empty($parseData)) {
            return $response;
        }

        $projectExist = self::query()
            ->orWhere('name', '=', $parseData['subject'])
            ->orWhere('url', '=', $parseData['url'])
            // ->orWhere('description', '=', $parseData['text'])
            ->select('id')
            ->first();

        if ($projectExist) {
            // no need to process same project twice
            return $response;
        }

        $companyId = null;
        if(!empty($parseData["client"]) && ( $parseData["client"] != "null|null null" && $parseData["client"] != "| " ) ) {
            $companyExist = Company::where('name', '=', $parseData['client'])->first();
        } else {
            $companyExist = '';
        }

        if (empty($companyExist)) {
            // Insert new company if not exists in our companies table
            $companySaveData = array_filter([
                'channel'  => $parseData['channel'] ?? '',
                'name'     =>  Company::sanitizeName($parseData['client']),
                'email'    => $parseData['email'] ?? '',
                'phone'    => $parseData['phone'] ?? '',
                'homepage' => $parseData['homepage'] ?? '',
                'city'     => $parseData['city'] ?? '',
                'state'    => $parseData['state'] ?? '',
                'country'  => $parseData['country'] ?? '',
                'zipcode'  => $parseData['zipcode'] ?? '',
                'language' => $parseData['language'] ?? ''
            ]);

            $companyId = Company::insertGetId($companySaveData);
        } elseif ($companyExist->is_banned == 0) {
            $companyId = $companyExist->id;
        }

        // Insert new project in our projects table
        $project = self::create([
            'name'        => $parseData['subject']?? '',
            'description' => $parseData['text']?? '',
            'channel'     => $parseData['channel']?? '',
            'url'         => $parseData['url']?? '',
            'source'      => $parseData['source']?? '',
            'categories'  => $parseData['categories']?? '',
            'language'    => $parseData['language']?? '',
        ]);

        // Insert data in project_companies table
        ProjectCompany::insert([
            'project_id' => $project->id,
            'company_id' => $companyId
        ]);

        $attachments = $parseData['attachment'] ?? '';

        $user = Auth::user();
        if (empty($user)) {
            $user = User::getBiddingUser();
        }

        $project->addMessage($parseData['channel'], $user->id, $parseData['text'] ?? '', null);
//        ProjectMessage::insert([
//            'project_id'  => $project->id,
//            'channel'     => $parseData['channel'],
//            'message'     => $parseData['text'],
//            'sender_id'    => $user->id,
//            'attachments' => json_encode($attachments)
//        ]);

        $detectedSkills = $project->detectSkills();
        //get project skills by name and description and save in skill table
        //$this->saveProjectSkills($saveProjectData['name'].' '.$saveProjectData['description'],$projectId);
        //bidPlace on recent added project by pass project id

        $response = SystemResponse::build(true, ['projectId' => $project->id, 'bid' => ($attemptBid & !empty($detectedSkills))]);

        return $response;
    }

    public function detectSkills()
    {
        // detect skills
        $detectedSkills = Skill::detectSkills($this->name . " " . $this->description);

        if (empty($detectedSkills)) {
            $detectedSkills = Skill::detectSkills($this->categories, "all", $isCommaString = true);
        }

        //set project skills
        $this->addSkills($detectedSkills);

        return $detectedSkills;
    }

    public function getSkills()
    {
        $skills = $this->projectSkills()->join('skills', 'skills.id', '=',
            'project_skills.skill_id')->select('skills.*')->get();
        if (empty($skills)) {
            $skills = $this->detectSkills();
        }

        return $skills;
    }
    
    public function getCompanies()
    {
        $company = $this->projectCompany()->join('companies', 'companies.id', '=',
            'project_companies.company_id')->select('companies.*')->get();
        
        return $company;
    }

    /**
     * save skills on the project
     *
     * @param $projectId integer mandatory
     * @param $aSkill    array mandatory
     */
    public function addSkills($skills)
    {
        $projectSkills = [];
        $skillIds = array_column($skills, 'id');
        foreach ($skillIds as $skillId) {
            $projectSkills[] = array('project_id' => $this->id, 'skill_id' => $skillId);
        }
        ProjectSkill::insert($projectSkills);
    }

    /**
     * @param                      $channel
     * @param                      $sender
     * @param                      $message
     * @param                      $event_type
     * @param int                  $sgMessageId
     * @param TemplateMessage|null $template
     * @param null                 $messageDetails
     * @param VisitorDetails|null  $visitorDetails
     */
    public function addMessage($channel, $sender, $message,$event_type,$sgMessageId = 0, TemplateMessage $template = null, $messageDetails = null, VisitorDetails $visitorDetails = null)
    {
        ProjectMessage::add($this, $channel, $sender, $message,$event_type,$sgMessageId, $template->id ?? null, $template->version ?? null, $messageDetails, $visitorDetails);
    }

    public function findSupplier()
    {
        //fetch suppliers by skills if not exist then fetch super admin user.
//                    $companyUser = $this->SupplierSkill->getSupplierCompanyUserBySkills($skillIds);
//                    $projectWorker = 0;
//                    if (empty($companyUser)) {
//                        $user = \App\User::first();
//                        $projectWorker = $user->id;
//                        $fullName = explode(' ', $user->name);
//                        $offeringUser = json_decode(json_encode([
//                            'fname'           => current($fullName),
//                            'lname'           => end($fullName),
//                            'phone_extension' => '',
//                            'email'           => $user->email
//                        ]));
//                    } else {
//                        $fullName = explode(' ', $companyUser->name);
//                        $projectWorker = $companyUser->id;
//                        $offeringUser = json_decode(json_encode([
//                            'fname'           => current($fullName),
//                            'lname'           => end($fullName),
//                            'phone_extension' => '',
//                            'email'           => $companyUser->email
//                        ]));
//                    }
    }

    public function assignUser($user)
    {
        ProjectWorker::create(['project_id' => $this->id, 'user_id' => $user->id]);
    }

    /**
     * @param bool   $isManual
     * @param bool   $addSignature
     * @param string $templateSlug
     *
     * @return bool
     */
    public function bid($isManual = false, $addSignature = true, $templateSlug = TemplateMessage::PLACE_BID_MESSAGE)
    {
        try {

            $detectedSkills = $this->getSkills();

            $template = TemplateMessage::getTemplate($templateSlug, $this->language);

            if (count($detectedSkills) > 0) { // only if skills found continue
                $offeringUser = User::getBiddingUser();
                $channel = BaseChannel::getChannel($this->channel);

                $offer = $this->getOffer($templateSlug, $offeringUser, $addSignature);
                $price = SystemResponse::getDataAttribute($offer, 'price') ?? '';
                $currency = SystemResponse::getDataAttribute($offer, 'currency') ?? Quote::BASE_CURRENCY;;
                // send bid
                $success = false;
                if ($channel && $channel->isBidAvailable()) {
                    $response = $channel->placeBid([
                        'url'      => $this->url,
                        'price'    => $price,
                        'currency' => $currency,
                        'title'    => $offer['title'] ?? '',
                        'content'  => $offer['content'] ?? '',
                    ]);

                    if (SystemResponse::isSuccess($response)) {

                        switch (SystemResponse::getDataAttribute($response, 'status')) {
                            case 'success':
                                $status = Project::STATUS_BID_PLACED;
                                $success = true;
                                break;
                            case 'closed':
                                $status = Project::STATUS_ARCHIVED;
                                break;
                            case 'error':
                                $status = Project::STATUS_PENDING;
                                break;
                            default:
                                $status = Project::STATUS_PENDING;
                                $success = true;
                        }
                    }

                    Slack::send(new LogJob($success, (SystemResponse::getDataAttribute($offer, 'price') ?? '') . (' BID ' . ($success ? 'SENT' : 'FAILED')),
                        array('channel' => $this->channel, 'content' => $offer['content'], 'url' => $this->url)));

                    if ($success) {
                        $messageData = [
                            'title'   => $offer['title'] ?? '',
                            'content' => $offer['content'] ?? ''
                        ];
                        $logMessage = "<b>Project Details</b> <br>";
                        $this->addMessage($this->channel, $offeringUser, Message::toString($messageData,$logMessage),ProjectMessage::EVENT_TYPE_BID_PLACE,'0', $template);
                    }
                }

                if (!$success && $this->canSendMessage()) {
                    // if no success or no channel bid method then fallback to send message
                    $success = $this->sendMessage([
                        'subject' => $offer['title'] ?? '',
                        'message' => $offer['content'] ?? '',
                        'type'  => ProjectMessage::EVENT_TYPE_BID_PLACE
                        //$offeringUser
                    ],'',$offeringUser,$template);

                    $status = ($success) ? Project::STATUS_BID_PLACED : $status = Project::STATUS_PENDING;

                    Slack::send(new LogJob($success, SystemResponse::getDataAttribute($offer, 'message') ?? ('MESSAGE ' . ($success ? 'SENT' : 'FAILED')),
                        array('channel' => $this->channel, 'content' => $offer['content'], 'url' => $this->url)));
                }

                // check bid placed or not
                if ($success) {

                    $this->automated = true;// !$isManual;
                    $this->bid_desc = $offer['content'];
                    //$this->is_hourly = 1;
                    $this->project_budget = $price;
                    $this->currency = $currency;
                    $this->status = $status;
                    $this->save();

                    $this->assignUser($offeringUser);

                    return true;
                }
            } else {
                Slack::send(new LogJob(false, 'Bid Failed: No skills detected',
                    array('channel' => $this->channel, 'url' => $this->url)));
            }

            // failed bid
            $this->automated = false;
            $this->save();

            return false;
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
        }
    }

    /**
     * @param array                $message
     * @param null                 $messageChannel
     * @param null                 $user
     * @param TemplateMessage|null $template
     *
     * @return bool
     */
    public function sendMessage(array $message, $messageChannel = null, $user = null, TemplateMessage $template = null)
    {

        if (empty($messageChannel)) {
            $messageChannel = $this->getMessageChannel();
        }

        if (empty($user)) {
            $user = Auth::user();
            if (empty($user)) {
                $user = User::getBiddingUser();
            }
        }

        $messageRefID = Message::send($messageChannel, $this, $message);

        if ($messageRefID) {
            // save message
            $logMessage = "<b>Project Details</b> <br>";

            $eventType = $message['type'] ?? null;
            $messageDetails = $message['message_details'] ?? null;

            $this->addMessage($this->channel, $user, Message::toString($message,$logMessage), $eventType, $messageRefID, $template, $messageDetails);
        }

        return !!$messageRefID ;
    }

    public function canSendMessage($messageChannel = null)
    {

        if (empty($messageChannel)) {
            $messageChannel = $this->getMessageChannel();
        }

        return Message::validateSend($messageChannel, $this);
    }

    public function getMessageChannel()
    {
        // for not defult is always the channel
        //TODO: add smarter logic according to the responses from client and activity (we want to get our of channel asap)
        $messageChannel = Message::CHANNEL_SCRAPER;

        if ($messageChannel == Message::CHANNEL_SCRAPER) {
            $messageChannel = Message::getMessageChannelOfScrapingChannel($this->channel);
        }

        return $messageChannel;
    }

    public static function getStatuses()
    {
        return $projectStatus = [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_NEGOTIATION,
            self::STATUS_BID_PLACED,
            self::STATUS_DONE,
            self::STATUS_ARCHIVED,
            self::STATUS_FOLLOW_UP,
            self::STATUS_IS_VIEWED,
            self::STATUS_ENGAGED,
            self::STATUS_NOT_RELEVANT,
        ];
    }

    public static function getStatusName($status)
    {
        switch ($status) {
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_BID_PLACED:
                return 'Bid Placed';
            case self::STATUS_NEGOTIATION:
                return 'Negotiation';
            case self::STATUS_IN_PROGRESS:
                return 'In Progress';
            case self::STATUS_DONE:
                return 'Done';
            case self::STATUS_ARCHIVED:
                return 'Archived';
            case self::STATUS_FOLLOW_UP:
                return 'Follow Up';
            case self::STATUS_IS_VIEWED:
                return 'Viewed';
            case self::STATUS_ENGAGED:
                return 'Engaged';
            case self::STATUS_NOT_RELEVANT:
                return 'Not Relevant';
            default:
                return 'Unknown';
        }
    }

    public static function getStatusLabel($status)
    {
        switch ($status) {
            case self::STATUS_PENDING:
                return "<label class='label label-warning'>" . self::getStatusName(self::STATUS_PENDING) . "</label>";
            case self::STATUS_BID_PLACED:
                return "<label class='label label-success'>" . self::getStatusName(self::STATUS_BID_PLACED) . "</label>";
            case self::STATUS_NEGOTIATION:
                return "<label class='label label-default'>" . self::getStatusName(self::STATUS_NEGOTIATION) . "</label>";
            case self::STATUS_IN_PROGRESS:
                return "<label class='label label-primary'>" . self::getStatusName(self::STATUS_IN_PROGRESS) . "</label>";
            case self::STATUS_DONE:
                return "<label class='label label-success'>" . self::getStatusName(self::STATUS_DONE) . "</label>";
            case self::STATUS_ARCHIVED:
                return "<label class='label label-default'>" . self::getStatusName(self::STATUS_ARCHIVED) . "</label>";
            case self::STATUS_FOLLOW_UP:
                return "<label class='label label-default'>" . self::getStatusName(self::STATUS_FOLLOW_UP) . "</label>";
            case self::STATUS_IS_VIEWED:
                return "<label class='label label-default'>" . self::getStatusName(self::STATUS_IS_VIEWED) . "</label>";
            case self::STATUS_ENGAGED:
                return "<label class='label label-default'>" . self::getStatusName(self::STATUS_ENGAGED) . "</label>";
            case self::STATUS_NOT_RELEVANT:
                return "<label class='label label-default'>" . self::getStatusName(self::STATUS_NOT_RELEVANT) . "</label>";
            default:
                return "<label class='label label-default'>Unknown</label>";
        }
    }

    public static function getAutomationLabel($automated)
    {
        if ($automated) {
            return '<label class=\"label label-success\"><i class=\"fa fa-regular fa-magic\"></i> Automation <i class=\"fa fa-regular fa-square-check\"></i></label>';
        } else {
            return '<label class=\"label label-default\"><i class=\"fa fa-regular fa-magic\"></i> Automation <i class=\"fa fa-regular fa-square\"></label>';
        }
    }


    public function getBidTemplateDetails($isPreview = false){
        $basePrice = 0;
        $skillsStr = "";
        $skillIds = array();
        $detectedSkills = $this->getSkills();

        foreach ($detectedSkills as $ind => $skill) {
            $basePrice += floatval($skill->hourly_rate);
            if ($ind > 0) {
                $skillsStr .= ", ";
            }
            $skillsStr .= $skill->keyword;
            array_push($skillIds, $skill->id);
        }

        if(!empty($skillsStr)) {
            $skillArray = explode(',',$skillsStr);
            $uniqueSkills = array_unique($skillArray);
            $skillsStr = implode(',',$uniqueSkills);
        }

        $basePrice = $basePrice / count($detectedSkills);
        $channelObj = BaseChannel::getChannel($this->channel);
        if (!$channelObj && !$isPreview) {
            throw new \Exception('channel ' . $this->channel . ' couldn\'t be found');
        }
        if($channelObj) {
            $currency = $channelObj->getCurrency();
        } else {
            $currency = Quote::BASE_CURRENCY;
        }
        $price = Quote::getPrice($basePrice, $currency);

        // take client first name
        $clientName = Company::sanitizeName($this->company()->name);
        if (!Company::isNameUnknown($clientName)) {
            $clientFName = current(explode(' ', $clientName));
            $clientFName = !empty($clientFName) ? $clientFName : $clientName;
        } else {
            $clientFName = '';
        }

        $phone = $this->company()->phone;
        $clientPhone = !empty($phone) ? $phone : '';
        $projectName = $this->name;
        $experience = $this->experience;

        // prepare offer to send
        $bidDetails = array(
            'clientFirstName'    => $clientFName,
            'skills'             => $skillsStr,
            'experience'         => $experience,
            'price'              => $price,
            'currency'           => $currency,
            'dollarPrice'        => round($basePrice),
            'projectName'        => $projectName,
            'clientPhone'        => $clientPhone,
            'channel'            => $this->channel,
        );

        return $bidDetails;
    }

    /**
     * @return mixed
     */
    public function todoProjectMessages()
    {
        return $this->hasMany(ProjectMessage::class,'project_id','id')
            ->where('event_type','=', ProjectMessage::EVENT_TYPE_COMMENT)->orderBy('id', 'DESC');
    }

    /**
     * @param      $templateSlug
     * @param      $user
     * @param bool $addSignature
     *
     * @return array
     * @throws \Exception
     */
    public function getOffer($templateSlug, $user, $addSignature = true,$isPreview = false) {
        $template = TemplateMessage::getTemplate($templateSlug, $this->language);
        $bidTemplateDetails = $this->getBidTemplateDetails($isPreview);
        $bidUserDetails = $user->getBidUserDetails();
        $bidTemplateDetails = array_merge($bidTemplateDetails, $bidUserDetails);

        $offer = $template->generate($this->id, $bidTemplateDetails,$this->url);

        if ($addSignature) {
            $emailSignatureMessage = $user->getEmailSignature($this->language, $this->id);
            $offer['content'] = $offer['content'] . TemplateMessage::LINE_SEPARATOR . $emailSignatureMessage['content'];
        }

        return $offer;
    }

    public function projectMessages()
    {
        return $this->hasMany(ProjectMessage::class,'project_id','id');
    }

    public static function createProjectWithCompany($parseData) {
        $name = "";
        if(!empty($parseData['first_name']) && !empty($parseData['last_name'])){
            $name = $parseData['first_name']." ".$parseData['last_name'];    
        }
        $companyExist = [];
        if (!empty($name) && $name!="") {
            $companyExist = Company::where('name', '=', $name)->first();    
        }

        $language = Language::detectLanguage((string) $parseData['project_name']);

        if (empty($companyExist)) {
            $companySaveData = array_filter([
                'channel'  => $parseData['channel'] ?? '',
                'name'     => $name ?? '',
                'email'    => $parseData['email'] ?? '',
                'phone'    => $parseData['phone'] ?? '',
                'homepage' => $parseData['homepage'] ?? '',
                'city'     => $parseData['city'] ?? '',
                'state'    => $parseData['state'] ?? '',
                'country'  => $parseData['country'] ?? '',
                'zipcode'  => $parseData['zipcode'] ?? '',
                'language' => $language,
            ]);

            $companyId = Company::insertGetId($companySaveData);
        } elseif ($companyExist->is_banned == 0) {
            $companyId = $companyExist->id;
        }

        $project = self::create([
            'name'        => $parseData['project_name']?? '',
            'description' => $parseData['project_description']?? '',
            'channel'     => $parseData['channel']?? '',
            'url'         => $parseData['url']?? '',
            'source'      => $parseData['source']?? '',
            'page_name'   => $parseData['page_name']?? '',
            'menu_name'   => $parseData['menu_name']?? '',
            'categories'  => $parseData['categories']?? '',
            'language'    => $language,
        ]);

        // Insert data in project_companies table
        ProjectCompany::insert([
            'project_id' => $project->id,
            'company_id' => $companyId,
            'expert_id'  => $parseData['expert_id']?? '',
        ]);
        $project->detectSkills();
        return true;
    }

    public function getActiveSender($isAutomated = true) {
        $projectMessageData = ProjectMessage::where('project_id', $this->id)->where('from_email',
            '!=', '0')->where('from_name', '!=', '0')->first();

        if ($projectMessageData && $projectMessageData->from_email) {
            $fromEmail = $projectMessageData->from_email ?? '';
            $fromName = $projectMessageData->from_name ?? '';
        } else {
            if ($isAutomated) {
                // bot default
                $fromEmail = LAConfigs::getByKey('default_email');
                $fromName = 'Development Team';
            } else {
                $user = Auth::user();
                if (!$user) {
                    $user = User::getBiddingUser();
                }
                $fromEmail = $user->getFromEmailAddress();
                $fromName = $user->getFromName();
            }
        }

        return ['email' => $fromEmail, 'name' => $fromName];
    }
}