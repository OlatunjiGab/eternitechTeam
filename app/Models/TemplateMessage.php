<?php

namespace App\Models;

use App\Helpers\ShortLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TemplateMessage extends Model
{

    const TEMPLATE_VARIABLE_PREFIX = '{{';
    const TEMPLATE_VARIABLE_SUFFIX = '}}';

    const LINE_SEPARATOR = " \n ";
    const PLACE_BID_MESSAGE = 'PLACE_BID_MESSAGE';
    const RESPONSE_MESSAGE = 'RESPONSE_MESSAGE';
    const WHATSAPP_MESSAGE = 'WHATSAPP_MESSAGE';
    const EMAIL_SIGNATURE = 'EMAIL_SIGNATURE';
    const SMS_WHATSAPP_LINK = 'SMS_WHATSAPP_LINK';
    const GET_PHONE_EMAIL_HEADING = 'GET_PHONE_EMAIL_HEADING';
    const GET_PHONE_EMAIL_SUB_HEADING = 'GET_PHONE_EMAIL_SUB_HEADING';
    const ETTRACKER_POPUP_CONTENT = 'ETTRACKER_POPUP_CONTENT';
    const TEMPLATE_LIST = [
        self::PLACE_BID_MESSAGE => self::PLACE_BID_MESSAGE,
        self::RESPONSE_MESSAGE => self::RESPONSE_MESSAGE,
        self::WHATSAPP_MESSAGE => self::WHATSAPP_MESSAGE,
        self::EMAIL_SIGNATURE => self::EMAIL_SIGNATURE,
        self::SMS_WHATSAPP_LINK => self::SMS_WHATSAPP_LINK,
        self::GET_PHONE_EMAIL_HEADING => self::GET_PHONE_EMAIL_HEADING,
        self::GET_PHONE_EMAIL_SUB_HEADING => self::GET_PHONE_EMAIL_SUB_HEADING,
        self::ETTRACKER_POPUP_CONTENT => self::ETTRACKER_POPUP_CONTENT,
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_LIST = [
        self::STATUS_ACTIVE => "Active",
        self::STATUS_INACTIVE => "Inactive",
    ];

    const PHONE_NUMBER = '(+1) 786-5040180';

    protected $guarded = [];

    protected $rules = [
            'slug' => 'required|unique:template_messages',
            'language' => 'required',
            'title' => 'required',
            'content' => 'required',
            'type' => 'required',
            'time' => 'required_if:type,==,1|nullable|integer',
            'period' => 'required_if:type,==,1|nullable|integer',

        ];

    protected $messages = [
				        'slug.required' => 'The field slug is required.',
				        'slug.unique' => 'Language :language already exist with this slug.',
				        'title.required' => 'The field title is required.',
				        'content.required' => 'The field content is required.',
				        'language.required' => 'The field language is required.',
				        'time.required_if' => 'The field time is required.',
				        'period.required_if' => 'The field period is required.',
        ];

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules,$this->messages);
        // return the result
        return $v;
    }

    public function getLanguage(){
        return $this->hasOne('App\Models\Language', 'code', 'language');
    }

    /**
     * @param        $key
     * @param string $language
     *
     * @return self
     */
    public static function getTemplate($key, $language = Language::DEFAULT) {
         $template = self::query()->whereSlug($key)->whereLanguage($language)->first();
         if ($template) {
             return $template;
         } else {
             throw new \Exception('No Template found: '. $key . '_' . $language);
         }
    }

    public function generate($projectId, $data,$leadURL = null) {
        $meetingShortLink = ShortLink::getMeetingShortLink($projectId);
        $twitterLink = ShortLink::getTwitterLink($projectId);
        $linkedinLink = ShortLink::getLinkedinLink($projectId);
        $instagramLink = ShortLink::getInstagramLink($projectId);
        $facebookLink = ShortLink::getFacebookLink($projectId);
        $whatsUpLink = ShortLink::getWhatsAppShortLink($projectId);
        $leadShortLink = ShortLink::getCrmLeadLink($projectId);
        $leadSkillShortLink = ShortLink::getLeadSkillShortLink($projectId);
        $homeShortLink = ShortLink::getHomepageShortLink($projectId);
        $landingShortLink = ShortLink::getLandingShortLink($projectId);
        $portfolioShortLink = ShortLink::getPortfolioShortLink($projectId);
        $templateReplacers = array(
            'leadURL'            => $leadURL,
            'whatsapp'           => $whatsUpLink,
            'leadShortLink'      => $leadShortLink,
            'leadSkillShortLink' => $leadSkillShortLink,
            'homeShortLink'      => $homeShortLink,
            'meetingShortLink'   => $meetingShortLink,
            'landingShortLink'   => $landingShortLink,
            'twitterLink'        => $twitterLink,
            'linkedinLink'       => $linkedinLink,
            'instagramLink'      => $instagramLink,
            'facebookLink'       => $facebookLink,
            'portfolioShortLink' => $portfolioShortLink
        );

        $data = array_merge($data, $templateReplacers);
        // replace all data keys to be template replaceble variables
        $templateKeys = array_keys($data);
        $templateKeys = preg_filter('/^/', self::TEMPLATE_VARIABLE_PREFIX, $templateKeys);
        $templateKeys = preg_filter('/$/', self::TEMPLATE_VARIABLE_SUFFIX, $templateKeys);

        $templateData = array_combine(
            $templateKeys,
            array_values($data)
        );
        $this->content = str_replace("{{clientPhone}}", self::PHONE_NUMBER, $this->content);
        
        return array(
            'title' => strtr($this->title, $templateData),
            'content' => strtr($this->content, $templateData),
            'data' => $data
        );
    }
}
