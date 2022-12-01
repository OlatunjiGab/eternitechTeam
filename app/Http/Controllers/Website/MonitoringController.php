<?php

namespace App\Http\Controllers\Website;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\CustomHelper;
use App\Helpers\Message;
use App\Helpers\Quote;
use App\Helpers\ShortLink;
use App\Helpers\VisitorDetails;
use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Language;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectCompany;
use App\Models\Skill;
use App\Models\TemplateMessage;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use App\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\ProjectClientActivity;
use App\Models\ProjectSkill;
use App\Models\FrontendClientActivities;
use App\Models\ProjectMessage;
use Illuminate\Support\Facades\Log;
use SendGrid\Mail\OpenTracking;
use SendGrid\Mail\TrackingSettings;
use SendGrid\Mail\From;
use SendGrid\Mail\To;
use App\Models\Response as ResponseModel;
use Config;
use LAConfigs;
use Mail;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use DB;

class MonitoringController extends Controller
{

    /**
     * create whatsapp message link with project detail
     *
     * @param Request $request
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */

    public function getWhatsAppUrl(Request $request)
    {
        $projectKey = $request->p;
        $projectId = ShortLink::getProjectIDByKey($projectKey);
        $project = Project::find($projectId);

        $offeringUser = User::getBiddingUser();
        $leadURL = $project->url;

        try {
            $message = $project->getOffer(TemplateMessage::WHATSAPP_MESSAGE, $offeringUser, false);
            $whatsappTemplateContent = $message['content'];
            if (empty($whatsappTemplateContent)) {
                $whatsappTemplateContent = "Hey i'm contacting you about \"$project->name\"";
            }
        } catch (\Exception $e) {
            $whatsappTemplateContent = "Hey i'm contacting you about \"$project->name\"";
        }
        if ($leadURL) {
            $whatsappTemplateContent .= " --- $leadURL";
        }

        $whatsAppURL = "https://api.whatsapp.com/send?phone=" . WHATSAPP_PHONE_NUMBER . "&text=" . urlencode($whatsappTemplateContent);

        return redirect($whatsAppURL);
    }

    /**
     * generate lead short link with projectID
     *
     * @param $id
     *
     * @return string
     */
    public function getLeadShortLink($id)
    {
        return ShortLink::getCrmLeadLink($id);
    }

    /**
     * project client short link click log store
     *
     * @param Request $request
     * @param         $slug
     * @param         $key
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function projectClientActivity(Request $request, $slug, $key)
    {
        $projectID = ShortLink::getProjectIDByKey($key);
        $project = Project::where('id', '=', $projectID)->first();

        if (!$project) {
            return abort(404);
        }

        $channel = BaseChannel::getChannel($project->channel);

        $url = $request->url();
//        if (!isset($request->browserName) && empty($request->browserName)) {
//            return view('website.get-user-agent-info', ['url' => $url]);
//        }

        $visitorDetails = new VisitorDetails($request);
        $templateId = null;
        $templateVersion = null;
        $userEngaged = 0;
        $user_id = (Auth::check()) ? Auth::user()->id : 0;

        $eventType = ShortLink::SLUGS_EVENT_TYPE[$slug] ?? null;

        if ($channel && $visitorDetails->isBot) {
            $eventType = $channel->getBotEventType();
        } else {
            // log client activity
            $insertArray = array(
                'project_id'             => $projectID,
                'user_id'                => $user_id,
                'type'                   => '',
                'url'                    => $url,
                'additional_information' => ''
            );
            ProjectClientActivity::create($insertArray);

            // update last touch point message as engaged
            if ($projectMessageData = ProjectMessage::where('project_id', '=', $projectID)->whereIn('event_type',
                ProjectMessage::EVENT_TYPES_TOUCH_POINTS)->orderBy('id', 'DESC')->first()) {
                $templateId = $projectMessageData->template_id ? : null;
                $templateVersion = $projectMessageData->version ? : null;
                $userEngaged = 1;
                $projectMessageData->user_engaged = 1;
                $projectMessageData->save();
            }
        }


        // add new timeline message
        if ($eventType) {
            $slugName = array_key_exists($slug, ShortLink::SLUG_NAMES) ? ShortLink::SLUG_NAMES[$slug] : $slug;
            $msg = $visitorDetails->isBot ? 'Viewed (bot)' : 'Clicked on';
            $messageString = "<b> " . $msg . ' ' . $slugName . " Link</b> Target: <a target='_blank' href='" . $url . "'>" . $url . "</a>";
            ProjectMessage::add($project, null, null, $messageString, $eventType, null,  $templateId, $templateVersion, null, $visitorDetails);
        }

        // update project status as viewed
        if ($project) {
            $project->updateStatus(Project::STATUS_IS_VIEWED);
        }

        switch ($slug) {
            case ShortLink::SLUG_LANDING_PAGE:
            case "landing":
                $project = Project::findOrFail($projectID);
                $projectSkills = $project->getSkills();
                $skillsStr = "";
                $basePrice = 0;
                $mainSkill = null;
                foreach ($projectSkills as $ind => $skill) {
                    if ($ind > 0) {
                        $skillsStr .= ", ";
                    } else {
                        $mainSkill = $skill;
                    }
                    $basePrice += floatval($skill->hourly_rate);
                    $skillsStr .= $skill->keyword;
                }
                $portfolios = Portfolio::with([
                    'skills' => function ($query) {
                        $query->select('skills.id', 'skills.keyword');
                    }
                ])->where('portfolios.is_live', '=', Portfolio::IS_YES)->whereHas('skills',
                    function ($qry) use ($skillsStr) {
                        $qry->whereIn('keyword', explode(', ', $skillsStr));
                    })->limit(3)->get()->toArray();
                $basePrice = $basePrice / count($projectSkills);
                $price = Quote::getPrice($basePrice, $project->currency);
                $isHourly = Project::IS_HOURLY_LIST[$project->is_hourly];
                $projectId = $project->id;
                $projectName = $project->name;
                $projectDescription = $project->description;
                $project->status = Project::STATUS_IS_VIEWED;
                $project->save();

                $clientName = '';
                $projectCompany = $project->getCompanies();
                if (!empty($projectCompany)) {
                    $company = $projectCompany;
                    $clientName = $company[0]->name;
                    $country = $company[0]->country;
                }

            return view('website.landing_page', [
                'projectName'        => $projectName,
                'projectDescription' => $projectDescription,
                'projectID'          => $key,
                'id'                 => $projectId,
                'mainSkill'          => $mainSkill,
                'skills'             => $skillsStr,
                'isHourly'           => $isHourly,
                'price'              => $price,
                'portfolios'         => $portfolios,
                'clientName'         => $clientName,
                'location'           => $country
            ]);
            break;
            case ShortLink::SLUG_COMMUNITY:
                if ($user_id) {
                    return redirect(config('laraadmin.adminRoute') . "/projects/" . $projectID);
                } else {
                    $project = Project::findOrFail($projectID);
                    $projectSkills = $project->getSkills();
                    $skillsStr = "";
                    $basePrice = 0;
                    foreach ($project->getSkills() as $ind => $skill) {
                        if ($ind > 0) {
                            $skillsStr .= ", ";
                        }
                        $basePrice += floatval($skill->hourly_rate);
                        $skillsStr .= $skill->keyword;
                    }
                    $basePrice = $basePrice / count($projectSkills);
                    $price = Quote::getPrice($basePrice, $project->currency);
                    $isHourly = Project::IS_HOURLY_LIST[$project->is_hourly];
                    $projectId = $project->id;
                    $projectName = $project->name;
                    $projectDescription = $project->description;

                    $project->status = Project::STATUS_IS_VIEWED;
                    $project->save();

                    return view('website.community-lead', [
                        'projectName'        => $projectName,
                        'projectDescription' => $projectDescription,
                        'projectID'          => $key,
                        'id'                 => $projectId,
                        'skills'             => $skillsStr,
                        'isHourly'           => $isHourly,
                        'price'              => $price
                    ]);
                }
                break;
            case ShortLink::SLUG_MEET:
                $url = ShortLink::getMeetingRedirectLink($key);

                return redirect($url);
                break;
            case ShortLink::SLUG_HOMEPAGE:
                $url = ShortLink::getWebsiteLeadUrl($key);

                return redirect($url);
                break;
            case ShortLink::SLUG_LEAD:
                if ($user_id) {
                    return redirect(config('laraadmin.adminRoute') . "/projects/" . $projectID);
                } else {
                    return redirect(config('laraadmin.adminRoute') . "/projects/" . $projectID . "?is_not_login=1")->withErrors(['msg' => 'login or register as a partner to see this lead']);
                }
                break;
            case ShortLink::SLUG_SKILL:
            case "p":
                $projectskill_id = ProjectSkill::select('skill_id')->where('project_id', $projectID)->first();
                $skillurl = Skill::select('url')->find(($projectskill_id->skill_id) ? $projectskill_id->skill_id : 0);
                $url = ($skillurl->url) ? $skillurl->url : "";
                $url = $url . "?projectID=$key";

                return redirect($url);
                break;
            case ShortLink::SLUG_WHATSAPP:
                // for get phone number
                if ($project && !VisitorDetails::isMobileDevice()) {
                    $company = $project->company();
                    if (empty($company->phone)) {
                        return redirect('/get-phone-number/' . $key);
                    }
                }

                $redirectURL = ShortLink::getWhatsAppLink($projectID);

                return redirect($redirectURL);
                break;
            case ShortLink::SLUG_PORTFOLIO:
                $url = ShortLink::getPortfolioRedirectLink($key);

                return redirect($url);
                break;
            default:
                return abort(404);
        }
    }

    /**
     * store frontend site user activity using ettracker.js
     *
     * @param Request $request
     *
     * @return string
     */
    public function frontendActivityAdd(Request $request)
    {
        $projectID = ShortLink::getProjectIDByKey($request->projectID);
        $project = Project::where('id', '=', $projectID)->first();
        $visitorDetails = new VisitorDetails($request);

        $templateId = null;
        $templateVersion = null;
        $userEngaged = 0;
        if ($projectMessageData = ProjectMessage::where('project_id', '=', $projectID)
            ->where('event_type', '=', ProjectMessage::EVENT_TYPE_EMAIL_SEND)
            ->orderBy('id', 'DESC')->first()) {
                $templateId = $projectMessageData->template_id ? : null;
                $templateVersion = $projectMessageData->version ? : null;
                $userEngaged = 1;
                $projectMessageData->user_engaged = 1;
                $projectMessageData->save();
        }

        $companyID = $request->companyID ? ShortLink::getProjectIDByKey($request->companyID) : null;

        $insertarray = array(
            'project_id'             => $projectID,
            'type'                   => $request->type,
            'url'                    => $request->tdText,
            'additional_information' => $request->title,
            'ip_address'             => $visitorDetails->ip,
            'browser_name'           => $visitorDetails->browserName,
            'os_name'                => $visitorDetails->osName,
            'city'                   => $visitorDetails->city,
            'country'                => $visitorDetails->country,
            'browser_version'        => $visitorDetails->browserVersion,
            'os_version'             => $visitorDetails->osVersion,
            'device_type'            => $visitorDetails->deviceType,
            'country_code'           => $visitorDetails->countryCode,
            'company_id'             => $companyID
        );
        FrontendClientActivities::create($insertarray);

        $messageString = "<b>User Activity: </b> $request->type target: <a target='_blank' href='$request->tdText'> $request->tdText </a> <br> <b> Additional information :</b> $request->type $request->title clicked";

        ProjectMessage::add($project, null, null, $messageString, ProjectMessage::EVENT_TYPE_USER_WEBSITE_ACTIVITY, null,  $templateId, $templateVersion, null, $visitorDetails);


        return "frontend Activity";
    }

    /**
     * store email reply using sendgrid webhook
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiveEmailResponse(Request $request)
    {
        $from = $request->input("from");
        $to = $request->input("to");
        $body = $request->input("text");
        $subject = $request->input("subject");

        $recipientName = explode("<", $to);
        preg_match("#<(.*?)>#", $from, $sender);
        preg_match("#<(.*?)>#", $to, $recipient);
        $senderAddr = $sender[1];
        $recipientAddr = $recipient[1];

        // extract the number between "+" and "@" in the email address, this would be the post ID
        //preg_match("#\_(.*?)@#", $recipientAddr, $postId);
        //if ($project = Project::find((int)$postId[1])) {
        preg_match("#Ref::(.*?)!#", $body, $leadId);
        if ($project = Project::find((int)$leadId[1])) {

            $project->status = Project::STATUS_ENGAGED;
            $project->save();

            $user = User::where('email', $senderAddr)->first();
            if (empty($user)) {
                $user = User::getBiddingUser();
            }

            $bodyText = explode('On ', $body);
            $replyText = $bodyText[0];

            $projectMessage = new ProjectMessage;
            $projectMessage->project_id = $project->id;
            $projectMessage->message = "<b> Replied to your email</b> From : <a href='mailto:" . $senderAddr . "'>" . $senderAddr . "</a> <br> Reply: " . $replyText;
            $projectMessage->sender_id = $user->id;
            $projectMessage->channel = $project->channel;
            $projectMessage->event_type = ProjectMessage::EVENT_TYPE_EMAIL_REPLY;
            $projectMessage->from_email = $recipientAddr;
            $projectMessage->from_name = $recipientName[0];
            $projectMessage->is_read = 0;
            $projectMessage->save();

            if ($projectMessageData = ProjectMessage::where('project_id', '=', $project->id)->where('event_type', '=',
                ProjectMessage::EVENT_TYPE_EMAIL_SEND)->orderBy('id', 'DESC')->first()) {
                $senderID = $projectMessageData->sender_id;
                $projectMessageData->user_engaged = 1;
                $projectMessageData->save();
                if ($senderID && !empty($senderID)) {
                    if ($toUser = User::where('id', $senderID)->first()) {
                        if (env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
                            $isEmailBounce = $toUser->company()->select('is_email_bounce')->first();
                            if (!$isEmailBounce['is_email_bounce']) {
                                // Send mail to User his new Password
                                Mail::send('emails.reply_forward_user', [
                                    'fromUser'  => $user,
                                    'toUser'    => $toUser,
                                    'subject'   => $subject,
                                    'replyText' => $body
                                ], function ($m) use ($toUser) {
                                    $m->from(LAConfigs::getByKey('default_email'), LAConfigs::getByKey('sitename'));
                                    $m->to($toUser->email, $toUser->name)->subject('Email reply received from user');
                                });
                                $emailBounced = CustomHelper::checkAndUpdateBounceFlagEmail($toUser->email,
                                    $toUser->company_id);
                            }
                        } else {
                            Log::info("Email reply received from user email: " . $toUser->email . " name:" . $toUser->name . " subject: " . $subject . " reply message: " . $body);
                        }
                    }
                }
            }
        }

        // in any case, return a 200 OK response so SendGrid knows we are done.
        return response()->json(["success" => true], 200);
    }

    /**
     * get project detail using ettracker.js
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjectDetails(Request $request)
    {
        $id = ShortLink::getProjectIDByKey($request->projectID);
        $project = Project::find($id);
        if ($project && $project->company()) {
            $company = $project->company();
            $clientName = $company->name;
            $projectName = $project->name;
            $offeringUser = User::getBiddingUser();
            try {
                $contentMessage = $project->getOffer(TemplateMessage::ETTRACKER_POPUP_CONTENT, $offeringUser, false);
                $contentMessageString = nl2br($contentMessage['content']);
                if (empty($contentMessageString)) {
                    $contentMessageString = "Hey $clientName,<br>
                                            Lets have a short call on $projectName.<br>
                                            and We'll need your email address and phone number.";
                }
            } catch (\Exception $e) {
                $contentMessageString = "Hey $clientName,<br>
                                        Lets have a short call on $projectName.<br>
                                        and We'll need your email address and phone number.";
            }
            $code = 200;
            $data['project'] = $project;
            $data['company'] = $company;
            $data['contentMessageString'] = $contentMessageString;
            $response = [
                'status'  => true,
                'message' => "Retrieved successfully...",
                'data'    => $data
            ];
        } else {
            $code = 404;
            $response = [
                'status'  => false,
                'message' => "Data not found",
                'data'    => []
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * set project detail using ettracker.js
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setProjectDetails(Request $request)
    {
        $clientMessage = '';
        $projectID = ShortLink::getProjectIDByKey($request->projectID);
        $project = Project::where('id', '=', $projectID)->first();
        $id = $request->companyID;
        if ($id) {
            $company = Company::find($id);
        } else {
            $company = $project->company();
        }

        $url = $request->currentUrl ?? $request->url;

        $msgStr = '';
        if ($company) {
            if ($request->companyPhone) {
                $company->phone = $request->companyPhone;
                $msgStr .= ' <b>Phone: </b>' . $request->companyPhone;
            }
            if ($request->companyEmail) {
                $company->email = $request->companyEmail;
                $msgStr .= ' <b>Email: </b>' . $request->companyEmail;
            }
            if ($request->clientName) {
                $company->name = $request->clientName;
                $msgStr .= ' <b>Name: </b>' . $request->clientName;
            }

            $company->save();

            $visitorDetails = new VisitorDetails($request);

            if ($request->clientMessage) {
                $messageString = "<b>Client send form message</b> " . $request->clientMessage . '<b> On url: </b>' . $url;
                $project->addMessage(null, null, $messageString, ProjectMessage::EVENT_TYPE_FORM_FILLED, null,  null, null, $visitorDetails);
            }

            if (!empty($request->companyPhone) || !empty($request->companyEmail)) {
                $messageString = "<b>Client filled popup form</b> " . $msgStr . '<b> On url: </b>' . $url;
                $project->addMessage(null, null, $messageString, ProjectMessage::EVENT_TYPE_FORM_FILLED, null,  null, null, $visitorDetails);

                $projectLink = url(config('laraadmin.adminRoute') . '/projects/' . $projectID);
                $clientFName = $company->name;
                $message = "<p><h4>Client added phone or email</h4></p>";
                $message .= "<strong> Client Name :</strong> $clientFName <br>";
                if ($request->companyPhone) {
                    $message .= "<strong> Client added Phone :</strong> $request->companyPhone <br>";
                }
                if ($request->companyEmail) {
                    $message .= "<strong> Client added Email :</strong> $request->companyEmail <br>";
                }
                if ($clientMessage) {
                    $message .= "<strong> Client added Message :</strong> $clientMessage <br>";
                }
                $message .= "<strong> Project Link :</strong> $projectLink <br>";


                if ($project = Project::where('id', '=', $projectID)->first()) {
                    $project->status = Project::STATUS_IS_VIEWED;
                    $project->save();
                }
            }
            $response = [
                'status'  => true,
                'message' => "data updated",
                'data'    => [],
            ];
        } else {
            $response = [
                'status'  => false,
                'message' => "Data not found",
                'data'    => []
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * check user login or not
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserLogin(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $response = [
                'status'  => true,
                'message' => "user is logged in",
                'data'    => ['user' => $user],
            ];
        } else {
            $response = [
                'status'  => false,
                'message' => "User not login",
                'data'    => ['message' => 'This lead is for registered partners only, Please login / register']
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * store log of email open using sendgrid webhook
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailEventResponse(Request $request)
    {
        $payload = $request->all();
        Slack::send(new LogJob(true, 'Incoming Email Event',
            array('channel' => BaseChannel::EMAIL, 'content' => print_r($payload, true)), LogJob::CHANNEL_EMAILS));
        $email = $payload[0]['email'];
        $event = $payload[0]['event'];
        $sendgridMessageID = $payload[0]['sg_message_id'];
        if (isset($event) && !empty($event) && $event == 'open') {
            $sgMessageID = explode('.', $sendgridMessageID);
            if ($projectMessage = ProjectMessage::where('sg_message_id',
                $sgMessageID[0])->where('event_type', ProjectMessage::EVENT_TYPE_EMAIL_SEND)->first()) {
                $projectId = $projectMessage->project_id;
                $templateId = $projectMessage->template_id ? : null;
                $templateVersion = $projectMessage->version ? : null;
                $projectMessage->user_engaged = 1;
                $projectMessage->save();
                $message = "<b>Mention Email Opened</b> email: <a href='mailto:" . $email . "'>" . $email . "</a>";

                // update if already is existed (to updated older versions)
                $projectMessage = ProjectMessage::where('project_id', $projectId)->where('sg_message_id',
                    $sgMessageID[0])->where('event_type',
                    ProjectMessage::EVENT_TYPE_EMAIL_OPEN)->whereDate('created_at', "=", \Carbon\Carbon::today());
                $projectMessageCount = $projectMessage->count();
                $projectMessageFirst = $projectMessage->first();
                if ($projectMessageFirst) {
                    $projectMessageFirst->template_id = $templateId;
                    $projectMessageFirst->version = $templateVersion;
                    $projectMessageFirst->user_engaged = 1;
                    $projectMessageFirst->save();
                }

                if ($project = Project::where('id', '=', $projectId)->first()) {
                    $project->status = Project::STATUS_IS_VIEWED;
                    $project->save();
                }

                $user = User::where('email', $email)->first();
                if (empty($user)) {
                    $user = User::getBiddingUser();
                }

                ProjectMessage::add($project, BaseChannel::EMAIL, $user->id, $message, ProjectMessage::EVENT_TYPE_EMAIL_OPEN, $sgMessageID[0],
                    $templateId, $templateVersion
                );
            }
        }

        return response()->json(["success" => true], 200);
    }

    /**
     * display phone and email input form page
     *
     * @param Request $request
     * @param         $id
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function getPhoneNumber(Request $request, $id)
    {
        $projectID = ShortLink::getProjectIDByKey($id);
        if ($id && $project = Project::find($projectID)) {
            $company = $project->company();
            $clientName = $company->name;
            $projectName = $project->name;

            $heading = "Hey $clientName, no WhatsApp on this device?";
            $subHeading = "Enter your mobile number and we will get back to you about \"$projectName\"";

            try {
                $offeringUser = User::getBiddingUser();
                $headingMessage = $project->getOffer(TemplateMessage::GET_PHONE_EMAIL_HEADING, $offeringUser, false,
                    true);
                $heading = $headingMessage['content'];

                $subHeadingMessage = $project->getOffer(TemplateMessage::GET_PHONE_EMAIL_SUB_HEADING, $offeringUser,
                    false, true);
                $subHeading = $subHeadingMessage['content'];

            } catch (\Exception $e) {
                Slack::send(new ExceptionCought($e));
            }

            $webWhatsAppURL = ShortLink::getWhatsAppLink($projectID);

            return view('website.get-phone-number', [
                'id'             => $projectID,
                'project'        => $project,
                'projectID'      => $id,
                'heading'        => $heading,
                'subHeading'     => $subHeading,
                'webWhatsAppURL' => $webWhatsAppURL
            ]);
        } else {
            return view('errors.error', [
                'title'   => 'invalid request',
                'message' => 'invalid request',
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
    public function storePhoneNumber(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email',
        ]);
        $project = Project::find($request->project_id);
        if ($validator->passes()) {
            if (!empty($request->project_id) && $project) {

                $visitorDetails = new VisitorDetails($request);

                if (!empty($request->phone)) {
                    $whatsUpLink = ShortLink::getWhatsAppShortLink($project->id);
                    $clientFName = '';
                    if ($company = $project->company()) {
                        $clientFName = $company->name;
                        $company->phone = $request->phone;
                        $company->save();

                        $messageString = "<b>Client entered his phone number from WhatsApp link</b> Phone: $request->phone";
                        $project->addMessage(null, null, $messageString, ProjectMessage::EVENT_TYPE_CLIENT_ADD_PHONE, null,  null, null, $visitorDetails);
                    }

                    if ($template = TemplateMessage::getTemplate(TemplateMessage::SMS_WHATSAPP_LINK,
                        $project->language)) {
                        $templateVariables = $project->getBidTemplateDetails();
                        $dynamicData = $template->generate($project->id, $templateVariables, $project->url);
                        $textMessage = $dynamicData['content'];
                    } else {
                        $textMessage = "Hey $clientFName, this is Eternitech, Click $whatsUpLink to continue on WhatsApp.";
                    }
                    $projectData = Project::find($request->project_id);
                    $message['message'] = $textMessage;
                    $smsResponse = Message::sendSMS($projectData, $message);
                    $messageString = "<b> SMS send event </b> Message:  $textMessage";
                    $user = User::getBiddingUser();
                    $project->addMessage($project->channel, $user, $messageString, ProjectMessage::EVENT_TYPE_SMS_SEND);
                }
                if (!empty($request->project_id) && !empty($request->email)) {
                    if ($company = $project->company()) {
                        $company->email = $request->email;
                        $company->save();

                        $messageString = "<b>Client entered his email from WhatsApp link</b> Email: $request->email";
                        $project->addMessage(null, null, $messageString,  ProjectMessage::EVENT_TYPE_CLIENT_ADD_EMAIL, null,  null, null, $visitorDetails);
                    }
                }

                if (!empty($request->phone) || !empty($request->email)) {
                    $projectLink = url(config('laraadmin.adminRoute') . '/projects/' . $project->id);
                    $company = $project->company();
                    $clientFName = $company->name;
                    $message = "<p><h4>Client added phone or email</h4></p>";
                    $message .= "<strong> Client Name :</strong> $clientFName <br>";
                    if ($request->phone) {
                        $message .= "<strong> Client added Phone :</strong> $request->phone <br>";
                    }
                    if ($request->email) {
                        $message .= "<strong> Client added Email :</strong> $request->email <br>";
                    }
                    $message .= "<strong> Project Link :</strong> $projectLink <br>";

                    $config = array(
                        'template' => 'send_message',
                        'subject'  => 'Client added phone or email from WhatsApp link',
                        'params'   => ["msg" => $message],
                        'from'     => [LAConfigs::getByKey('default_email')],
                        'to'       => [Config::get('constant.adminEmailAddress')]
                    );
                    Message::sendSystemEmail($config);
                }

                $project->status = Project::STATUS_ENGAGED;
                $project->save();
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $redirectLink = ShortLink::SHORT_LINK . "/?projectID=" . $request->projectID;

        return redirect($redirectLink);
    }

    /**
     * for get os font-awesome icon html using $osName
     *
     * @param string $osName
     *
     * @return string
     */
    public function getOsIcon($osName = "Windows")
    {
        switch ($osName) {
            case "Windows":
                $osIcon = "fa-windows";
                break;
            case "Mac OS":
            case "Mac OS X":
            case "iOS":
            case "Macintosh":
                $osIcon = "fa-apple";
                break;
            case "Linux":
            case "UNIX":
                $osIcon = "fa-linux";
                break;
            case "Android":
                $osIcon = "fa-android";
                break;
            default:
                $osIcon = "fa-desktop";
        }
        $osIconString = "<i class='fa $osIcon' aria-hidden='true'></i>";

        return $osIconString;
    }

    public function subscribeFacebookWebhook(Request $request)
    {
        $challenge = $request->hub_challenge;
        $verify_token = $request->hub_verify_token;
        if ($verify_token === 'CRM') {
            echo $challenge;
        }
    }

    public function hireDeveloperForm(Request $request)
    {

        $status = false;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        $parseData['project_name'] = $parseData['project_name'] ? : "lead generate from hire a developer form";
        $parseData['project_description'] = $parseData['project_description']
            ? : "lead generate from hire a developer form";
        $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
        $parseData['source'] = Project::SOURCE_HIRE_DEVELOPER;
        $parseData['page_name'] = $parseData['page_name'] ? : "";
        $parseData['menu_name'] = $parseData['menu_name'] ? : "";

        $message = "Thank you for submitting your details. One of our representatives will introduce you to a Ninja soon!";
        $status = Project::createProjectWithCompany($parseData);

        $response = [
            'status'  => $status,
            'message' => $message,
            'errors'  => (object)[],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function getQuoteForm(Request $request)
    {

        $status = false;

        $validator = Validator::make($request->all(), [
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email',
            'phone'               => 'required',
            'project_name'        => 'required',
            'project_description' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        $parseData['source'] = Project::SOURCE_GET_A_QUOTE;
        $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
        $parseData['page_name'] = $parseData['page_name'] ? : "";
        $parseData['menu_name'] = $parseData['menu_name'] ? : "";

        $message = "lead added successfully!";
        $status = Project::createProjectWithCompany($parseData);

        $response = [
            'status'  => $status,
            'message' => $message,
            'errors'  => (object)[],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function buildYourTeamForm(Request $request)
    {

        $status = false;

        $validator = Validator::make($request->all(), [
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email',
            'phone'               => 'required',
            'project_name'        => 'required',
            'project_description' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        $parseData['source'] = Project::SOURCE_BUILD_YOUR_TEAM;
        $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
        $parseData['categories'] = $parseData['additional_message'] ? : '';
        $parseData['page_name'] = $parseData['page_name'] ? : "";
        $parseData['menu_name'] = $parseData['menu_name'] ? : "";

        $message = "lead added successfully!";
        $status = Project::createProjectWithCompany($parseData);

        $response = [
            'status'  => $status,
            'message' => $message,
            'errors'  => (object)[],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function meetingForm(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        $parseData['first_name'] = $parseData['name'] ? : '';
        $parseData['project_name'] = $parseData['project_name'] ? : "lead generate from meeting form";
        $parseData['project_description'] = $parseData['project_description'] ? : "lead generate from meeting form";
        $parseData['source'] = Project::SOURCE_MEETING;
        $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
        $parseData['categories'] = $parseData['additional_message'] ? : '';
        $parseData['page_name'] = $parseData['page_name'] ? : "";
        $parseData['menu_name'] = $parseData['menu_name'] ? : "";

        if ($request->has('email') && !empty($request->email)) {
            $email = $request->email;
            $name = $request->name;
            $date = $request->date;
            $time = $request->time;
            $config = array(
                'sendAs'   => 'html',
                'template' => 'book_a_meeting',
                'subject'  => 'meeting Booked',
                'params'   => ["name" => $name, "email" => $email, 'date' => $date, 'time' => $time],
                'from'     => [Config::get('constant.noReplyEmailAddress')],
                'to'       => [Config::get('constant.fromEmailAddress')],
            );
            //Message::sendCustomEmail($config);
        }

        $message = "lead added successfully!";
        $status = Project::createProjectWithCompany($parseData);

        $response = [
            'status'  => $status,
            'message' => $message,
            'errors'  => (object)[],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function contactForm(Request $request)
    {

        $status = false;

        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        $parseData['first_name'] = $parseData['name'] ? : '';
        $parseData['project_name'] = $parseData['project_name'] ? : "lead generate from contact form";
        $parseData['project_description'] = $parseData['message'] ? : "lead generate from contact form";
        $parseData['source'] = Project::SOURCE_CONTACT;
        $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
        $parseData['categories'] = $parseData['additional_message'] ? : '';
        $parseData['page_name'] = $parseData['page_name'] ? : "";
        $parseData['menu_name'] = $parseData['menu_name'] ? : "";

        $message = "lead added successfully!";
        $status = Project::createProjectWithCompany($parseData);

        $response = [
            'status'  => $status,
            'message' => $message,
            'errors'  => (object)[],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function checkoutForm(Request $request)
    {

        $status = false;

        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'required',
            'country' => 'string',
            'url'     => 'active_url',
        ]);

        if ($validator->fails()) {

            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        $parseData['first_name'] = $parseData['name'] ? : '';
        $parseData['project_name'] = $parseData['project_name'] ? : "lead generate from checkout form";
        $parseData['project_description'] = $parseData['message'] ? : "lead generate from checkout form";
        $parseData['source'] = Project::SOURCE_CHECKOUT;
        $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
        $parseData['categories'] = $parseData['additional_message'] ? : '';
        $parseData['page_name'] = $parseData['page_name'] ? : "";
        $parseData['menu_name'] = $parseData['menu_name'] ? : "";

        $message = "lead added successfully!";
        $status = Project::createProjectWithCompany($parseData);

        $response = [
            'status'  => $status,
            'message' => $message,
            'errors'  => (object)[],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function onlineTools(Request $request)
    {
        $status = false;
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $parseData = $request->all();
        if (isset($parseData['message']) && $parseData['message'] == 'Email-Popup-Lead') {
            $parseData['first_name'] = $parseData['name'] ? : '';
            $parseData['project_name'] = $parseData['page_name'] ? : "lead generate from online tools";
            $parseData['project_description'] = $parseData['message'] ? : "lead generate from online tools";
            $parseData['source'] = Project::SOURCE_ONLINE_TOOLS;
            $parseData['channel'] = $parseData['channel'] ? : BaseChannel::CRM;
            $parseData['page_name'] = $parseData['page'] ? : "";
            $parseData['menu_name'] = $parseData['menu_name'] ? : "";

            $message = "lead added successfully!";
            $status = Project::createProjectWithCompany($parseData);
            $request['date_time'] = date('Y-m-d H:i:s');

            $response = [
                'status'  => $status,
                'message' => $message,
                'data'    => ['lead' => $request->all()],
            ];
        } else {
            $status = true;
            $message = "You are not allowed to add lead!";
            $response = [
                'status'  => $status,
                'message' => $message,
                'data'    => ['lead' => $request->all()],
            ];
        }

        return response()->json($response, Response::HTTP_OK);
    }

    public function getOnlineTools(Request $request)
    {
        $status = false;
        $validator = Validator::make($request->all(), [
            'source' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }
        $data = [];
        $message = "No Online Tools lead found!";
        $parseData = $request->all();
        $sourceList = Project::SOURCE_LIST;
        $sourceKey = array_search($parseData['source'], $sourceList);
        $project = Project::where('source', $sourceKey)->get();
        if (!empty($project)) {
            foreach ($project as $key => $value) {
                $company = $value->company();
                $data[$key]['page_name'] = $value['name'] ? : '';
                $data[$key]['email'] = $company->email ? : '';
                $data[$key]['message'] = $value['description'] ? : '';
                $data[$key]['date_time'] = date('Y-m-d H:i:s', strtotime($value['created_at'])) ? : '';
            }
            $status = true;
            $message = "Online Tools lead fetched successfully!";
        }

        $response = [
            'status'  => $status,
            'message' => $message,
            'data'    => ['lead' => $data],
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function addRatings(Request $request)
    {
        $status = false;
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => $status,
                'message' => "validation error",
                'errors'  => $validator->errors(),
            ];

            return response()->json($response, Response::HTTP_OK);
        }
        $parseData = $request->all();
        $ratingData['page_name'] = $parseData['page_name'] ? : '';
        $ratingData['rating_date_time'] = $parseData['rating_date_time'] ? : date('Y-m-d H:i:s');
        $ratingData['vote_count'] = $parseData['vote_count'] ? : 0;
        $ratingData['average_rating'] = $parseData['average_rating'] ? : 0;

        $ratingData['created_at'] = date('Y-m-d H:i:s');
        $ratingData['updated_at'] = date('Y-m-d H:i:s');
        $ratingId = \DB::table('ratings')->insertGetId($ratingData);
        if ($ratingId) {
            $status = true;
        }

        $message = "rating added successfully!";
        $response = [
            'status'  => $status,
            'message' => $message,
            'data'    => ['rating' => $request->all()],
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
