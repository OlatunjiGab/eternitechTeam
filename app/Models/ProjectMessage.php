<?php

namespace App\Models;

use App\Helpers\VisitorDetails;
use Illuminate\Database\Eloquent\Model;

class ProjectMessage extends Model
{
	public $timestamps = true;
	
    protected $guarded = [];

    // todo: refactor to type (message)
    const CHANNEL_COMMENT = 'comment';
    const CHANNEL_BID = 'bid';

    const EVENT_TYPE_WHATSAPP_LINK         = 1;
    const EVENT_TYPE_SKILL_SHORT_LINK      = 2;
    const EVENT_TYPE_LEAD_SHORT_LINK       = 3;
    const EVENT_TYPE_EMAIL_OPEN            = 4;
    const EVENT_TYPE_CALL                  = 5;
    const EVENT_TYPE_USER_WEBSITE_ACTIVITY = 6;
    const EVENT_TYPE_HOMEPAGE_SHORT_LINK   = 7;
    const EVENT_TYPE_MEETING_SHORT_LINK    = 8;
    const EVENT_TYPE_EMAIL_SEND            = 9;
    const EVENT_TYPE_EMAIL_REPLY           = 10;
    const EVENT_TYPE_COMMENT               = 11;
    const EVENT_TYPE_BID_PLACE             = 12;
    const EVENT_TYPE_SMS_SEND              = 13;
    const EVENT_TYPE_CLIENT_ADD_PHONE      = 14;
    const EVENT_TYPE_CLIENT_ADD_EMAIL      = 15;
    const EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK = 16;
    const EVENT_TYPE_LANDING_SHORT_LINK        = 17;
    const EVENT_TYPE_EMAIL_CLOSE               = 18;
    const EVENT_TYPE_PORTFOLIO_SHORT_LINK      = 19;
    const EVENT_TYPE_VIEWED                    = 20;

    const EVENT_TYPE_FORM_FILLED           = 21;
    const EVENT_TYPE_AUTO_FOLLOWUP         = 22;

    const EVENT_TYPE_NAMES              = [
        self::EVENT_TYPE_WHATSAPP_LINK         => 'WhatsApp shortlink clicked',
        self::EVENT_TYPE_SKILL_SHORT_LINK      => 'skill shortlink clicked',
        self::EVENT_TYPE_LEAD_SHORT_LINK       => 'Lead shortlink clicked',
        self::EVENT_TYPE_EMAIL_OPEN            => 'Email opened',
        self::EVENT_TYPE_CALL                  => 'Phone Call',
        self::EVENT_TYPE_USER_WEBSITE_ACTIVITY => 'User website activity (url)',
        self::EVENT_TYPE_HOMEPAGE_SHORT_LINK   => 'Homepage shortlink clicked',
        self::EVENT_TYPE_MEETING_SHORT_LINK    => 'Meeting shortlink clicked',
        self::EVENT_TYPE_EMAIL_SEND            => 'Email Sent (out)',
        self::EVENT_TYPE_EMAIL_REPLY           => 'Email Replied (in)',
        self::EVENT_TYPE_COMMENT               => 'Comment added',
        self::EVENT_TYPE_BID_PLACE             => 'Bid placed',
        self::EVENT_TYPE_SMS_SEND              => 'SMS Sent',
        self::EVENT_TYPE_CLIENT_ADD_PHONE          => 'Client added phone (by form)',
        self::EVENT_TYPE_CLIENT_ADD_EMAIL          => 'Client added email (by form)',
        self::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK => 'Community lead shortlink clicked',
        self::EVENT_TYPE_LANDING_SHORT_LINK        => 'Landing page shortlink clicked',
        self::EVENT_TYPE_EMAIL_CLOSE               => 'Email not opened',
        self::EVENT_TYPE_PORTFOLIO_SHORT_LINK      => 'Portfolio shortlink clicked',
        self::EVENT_TYPE_VIEWED                    => 'Client viewed message (on channel)',
        self::EVENT_TYPE_FORM_FILLED               => 'Client filled form',
        self::EVENT_TYPE_AUTO_FOLLOWUP             => 'Automated Followup sent',
    ];

    const EVENT_TYPE_EMAIL_ACTION_NAMES = [
        self::EVENT_TYPE_EMAIL_OPEN  => 'Email open event',
        self::EVENT_TYPE_EMAIL_SEND  => 'Email Send event',
        self::EVENT_TYPE_EMAIL_REPLY => 'Email Reply event',
        self::EVENT_TYPE_EMAIL_CLOSE => 'Email close event',
    ];

    const EVENT_TYPES_TOUCH_POINTS = [
        self::EVENT_TYPE_CALL,
        self::EVENT_TYPE_EMAIL_SEND,
        self::EVENT_TYPE_BID_PLACE,
        self::EVENT_TYPE_SMS_SEND,
        self::EVENT_TYPE_AUTO_FOLLOWUP,
    ];

    const EVENT_TYPES_LEADS = [
        self::EVENT_TYPE_LEAD_SHORT_LINK,
        self::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK
    ];

    const EVENT_TYPES_USERS_ACTIONS =[
        self::EVENT_TYPE_WHATSAPP_LINK         => self::EVENT_TYPE_WHATSAPP_LINK,
        self::EVENT_TYPE_SKILL_SHORT_LINK      => self::EVENT_TYPE_SKILL_SHORT_LINK,
        self::EVENT_TYPE_EMAIL_OPEN            => self::EVENT_TYPE_EMAIL_OPEN,
        self::EVENT_TYPE_USER_WEBSITE_ACTIVITY => self::EVENT_TYPE_USER_WEBSITE_ACTIVITY,
        self::EVENT_TYPE_HOMEPAGE_SHORT_LINK   => self::EVENT_TYPE_HOMEPAGE_SHORT_LINK,
        self::EVENT_TYPE_MEETING_SHORT_LINK    => self::EVENT_TYPE_MEETING_SHORT_LINK,
        self::EVENT_TYPE_EMAIL_REPLY           => self::EVENT_TYPE_EMAIL_REPLY,
        self::EVENT_TYPE_CLIENT_ADD_PHONE      => self::EVENT_TYPE_CLIENT_ADD_PHONE,
        self::EVENT_TYPE_CLIENT_ADD_EMAIL      => self::EVENT_TYPE_CLIENT_ADD_EMAIL,
        self::EVENT_TYPE_LANDING_SHORT_LINK    => self::EVENT_TYPE_LANDING_SHORT_LINK,
        self::EVENT_TYPE_PORTFOLIO_SHORT_LINK      => self::EVENT_TYPE_PORTFOLIO_SHORT_LINK,
        self::EVENT_TYPE_VIEWED      => self::EVENT_TYPE_VIEWED,
        self::EVENT_TYPE_FORM_FILLED => self::EVENT_TYPE_FORM_FILLED,
    ];

    const EVENT_TYPES_USERS_ACTIONS_NAMES =[
        self::EVENT_TYPE_WHATSAPP_LINK         => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_WHATSAPP_LINK],
        self::EVENT_TYPE_SKILL_SHORT_LINK      => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_SKILL_SHORT_LINK],
//        self::EVENT_TYPE_LEAD_SHORT_LINK     => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_LEAD_SHORT_LINK],
        self::EVENT_TYPE_EMAIL_OPEN            => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_EMAIL_OPEN],
        self::EVENT_TYPE_CALL                  => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_CALL],
        self::EVENT_TYPE_USER_WEBSITE_ACTIVITY => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_USER_WEBSITE_ACTIVITY],
        self::EVENT_TYPE_HOMEPAGE_SHORT_LINK   => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_HOMEPAGE_SHORT_LINK],
        self::EVENT_TYPE_MEETING_SHORT_LINK    => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_MEETING_SHORT_LINK],
        self::EVENT_TYPE_EMAIL_REPLY           => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_EMAIL_REPLY],
        self::EVENT_TYPE_CLIENT_ADD_PHONE      => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_CLIENT_ADD_PHONE],
        self::EVENT_TYPE_CLIENT_ADD_EMAIL      => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_CLIENT_ADD_EMAIL],
//        self::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK],
        self::EVENT_TYPE_LANDING_SHORT_LINK        => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_LANDING_SHORT_LINK],
        self::EVENT_TYPE_PORTFOLIO_SHORT_LINK      => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_PORTFOLIO_SHORT_LINK],
        self::EVENT_TYPE_VIEWED      => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_VIEWED],
        self::EVENT_TYPE_FORM_FILLED      => self::EVENT_TYPE_NAMES[self::EVENT_TYPE_FORM_FILLED]
    ];

    const EVENT_TYPES_EMAIL_ACTIONS = [
        self::EVENT_TYPE_BID_PLACE   => self::EVENT_TYPE_BID_PLACE,
        self::EVENT_TYPE_EMAIL_SEND  => self::EVENT_TYPE_EMAIL_SEND,
        self::EVENT_TYPE_EMAIL_REPLY => self::EVENT_TYPE_EMAIL_REPLY,
    ];

    public static function getEventTypeIcon($event_type){
        switch ($event_type) {
            case self::EVENT_TYPE_WHATSAPP_LINK:
                $eventTypeIcon = "fa-whatsapp bg-green-active";
                break;
            case self::EVENT_TYPE_SKILL_SHORT_LINK:
                $eventTypeIcon = "fa-area-chart bg-yellow";
                break;
            case self::EVENT_TYPE_LEAD_SHORT_LINK:
                $eventTypeIcon = "fa-link bg-yellow";
                break;
            case self::EVENT_TYPE_EMAIL_OPEN:
                $eventTypeIcon = "fa-envelope-o bg-yellow";
                break;
            case self::EVENT_TYPE_CALL:
                $eventTypeIcon = "fa-phone bg-yellow";
                break;
            case self::EVENT_TYPE_USER_WEBSITE_ACTIVITY:
                $eventTypeIcon = "fa-user-secret bg-yellow";
                break;
            case self::EVENT_TYPE_HOMEPAGE_SHORT_LINK:
                $eventTypeIcon = "fa-home bg-yellow";
                break;
            case self::EVENT_TYPE_MEETING_SHORT_LINK:
                $eventTypeIcon = "fa-handshake-o bg-yellow";
                break;
            case self::EVENT_TYPE_EMAIL_SEND:
            case self::EVENT_TYPE_EMAIL_CLOSE:
            case self::EVENT_TYPE_EMAIL_REPLY:
                $eventTypeIcon = "fa-envelope bg-yellow";
                break;
            case self::EVENT_TYPE_SMS_SEND:
            case self::EVENT_TYPE_COMMENT:
                $eventTypeIcon = "fa-commenting bg-yellow";
                break;
            case self::EVENT_TYPE_AUTO_FOLLOWUP:
                $eventTypeIcon = "fa-magic bg-yellow";
                break;
            case self::EVENT_TYPE_BID_PLACE:
                $eventTypeIcon = "fa-gavel bg-yellow";
                break;
            case self::EVENT_TYPE_CLIENT_ADD_PHONE:
            case self::EVENT_TYPE_CLIENT_ADD_EMAIL:
                $eventTypeIcon = "fa-plus-square bg-yellow";
                break;
            case self::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK:
                $eventTypeIcon = "fa-user-md bg-yellow";
                break;
            case self::EVENT_TYPE_LANDING_SHORT_LINK:
                $eventTypeIcon = "fa-file-text bg-yellow";
                break;
            case self::EVENT_TYPE_PORTFOLIO_SHORT_LINK:
                $eventTypeIcon = "fa-comment bg-yellow";
                break;
            case self::EVENT_TYPE_VIEWED:
                $eventTypeIcon = "fa-eye bg-yellow";
                break;
            default:
                $eventTypeIcon = "fa-comment bg-yellow";
        }
        $eventTypeIconString = "<i class='fa $eventTypeIcon'></i>";
        return $eventTypeIconString;
    }

    public static function add($project, $channel, $sender, $message,$event_type,$sgMessageId = 0, $templateId = null, $templateVersion = null, $messageDetails = null, VisitorDetails $visitorDetails = null)
    {

        $data = [
            'project_id' => $project->id,
            'channel'    => $channel,
            'sender_id'  => $sender->id,
            'message'    => $message,
            'event_type' => $event_type ?? null,
            'message_details' => $messageDetails ?? null,
            'sg_message_id' => $sgMessageId,
            'template_id' => $templateId ?? null,
            'version' => $templateVersion ?? null,
        ];

        if ($visitorDetails) {
            $data['user_agent'] = $visitorDetails->ua ?? null;
            $data['ip_address'] = $visitorDetails->ip ?? null;
            $data['city'] = $visitorDetails->city ?? null;
            $data['country'] = $visitorDetails->country ?? null;
            $data['browser_name'] = $visitorDetails->browserName ?? null;
            $data['os_name'] = $visitorDetails->osName ?? null;
            $data['os_version'] = $visitorDetails->osVersion ?? null;
            $data['browser_version'] = $visitorDetails->browserVersion ?? null;
            $data['device_type'] = $visitorDetails->deviceType ?? null;
            $data['country_code'] = $visitorDetails->countryCode ?? null;
            $data['user_engaged'] = !$visitorDetails->isBot;
        }

        $projectMessage = self::create($data);

        AutomatedMessage::scheduleAutomatedFollowup($project, $projectMessage);
    }
}
