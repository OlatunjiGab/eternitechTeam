<?php
namespace App\Helpers;

use App\Models\ProjectMessage;
use Exception;
use Illuminate\Support\HtmlString;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Mail;
use Config;

/**
 * Created by Gopal.
 *
 * @descriptions : that class contain most common function which we will use in entire project 
 * 
 */

class ShortLink
{

    const SHORTLINK_BASE_DOMAIN = 'crm.eternitech.com';
    const SHORT_LINK            = 'https://eterni.tech';
    const FULL_LINK             = 'https://crm.eternitech.com';
    const FRONTEND_DOMAIN       = 'https://eternitech.com';
    const FRONTEND_MEETING_LINK = 'https://eternitech.com/book-a-meeting';
    const TWITTER_LINK          = 'https://twitter.com/Eternitech';
    const LINKEDIN_LINK         = 'https://www.linkedin.com/company/eternitech';
    const INSTAGRAM_LINK        = 'https://www.instagram.com/eternitech.it';
    const FACEBOOK_LINK         = 'https://www.facebook.com/Eternitechgroup/';

    const SLUG_WHATSAPP     = 'w';
    const SLUG_WHATSAPP_URL = 'wa';
    const SLUG_LEAD         = 'lead';
    const SLUG_COMMUNITY    = 'community';
    const SLUG_LANDING_PAGE = 'lp';
    const SLUG_PORTFOLIO    = 'portfolio';
    const SLUG_SKILL        = 's';
    const SLUG_HOMEPAGE     = 'h';
    const SLUG_MEET         = 'meet';

    const SLUG_NAMES = [
        self::SLUG_WHATSAPP     => 'whatsapp',
        self::SLUG_WHATSAPP_URL => 'whatsapp',
        self::SLUG_LEAD         => 'lead',
        self::SLUG_COMMUNITY    => 'community lead',
        self::SLUG_LANDING_PAGE => 'landing page',
        self::SLUG_PORTFOLIO    => 'portfolio page',
        self::SLUG_SKILL        => 'skill page',
        self::SLUG_HOMEPAGE     => 'homepage',
        self::SLUG_MEET         => 'book a meeting',
    ];

    const SLUGS_EVENT_TYPE = [
        self::SLUG_WHATSAPP     => ProjectMessage::EVENT_TYPE_WHATSAPP_LINK,
        self::SLUG_WHATSAPP_URL => ProjectMessage::EVENT_TYPE_WHATSAPP_LINK,
        self::SLUG_LEAD         => ProjectMessage::EVENT_TYPE_LEAD_SHORT_LINK,
        self::SLUG_COMMUNITY    => ProjectMessage::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK,
        self::SLUG_LANDING_PAGE => ProjectMessage::EVENT_TYPE_LANDING_SHORT_LINK,
        self::SLUG_PORTFOLIO    => ProjectMessage::EVENT_TYPE_PORTFOLIO_SHORT_LINK,
        self::SLUG_SKILL        => ProjectMessage::EVENT_TYPE_SKILL_SHORT_LINK,
        self::SLUG_HOMEPAGE     => ProjectMessage::EVENT_TYPE_HOMEPAGE_SHORT_LINK,
        self::SLUG_MEET         => ProjectMessage::EVENT_TYPE_MEETING_SHORT_LINK
    ];

    public static function getProjectKey($projectId) {
        $key =  base64_encode((int)$projectId);
        return str_replace('=', '', $key);
    }

    public static function getProjectIDByKey($key) {
        return  $projectId = (int) base64_decode($key);
    }

    /**
     * @param $projectId
     *
     * @return string
     */
    public static function getWhatsAppLink($projectId){
        return self::SHORT_LINK.'/' . self::SLUG_WHATSAPP_URL . '?p=' . self::getProjectKey($projectId);
    }

    public static function getWhatsAppShortLink($projectId) {
        return self::SHORT_LINK. '/' . self::SLUG_WHATSAPP . '/' . self::getProjectKey($projectId);
    }

    public static function getCrmLeadLink($projectId) {
        return self::FULL_LINK. '/' . self::SLUG_LEAD . '/' . self::getProjectKey($projectId);
    }

    public static function getCrmCommunityLeadLink($projectId)
    {
        return self::FULL_LINK . '/' . self::SLUG_COMMUNITY . '/' . self::getProjectKey($projectId);
    }

    public static function getLandingShortLink($projectId)
    {
        return self::SHORT_LINK . '/' . self::SLUG_LANDING_PAGE . '/' . self::getProjectKey($projectId);
    }

    public static function getPortfolioShortLink($projectId) {
        return self::SHORT_LINK. '/' . self::SLUG_PORTFOLIO . '/' . self::getProjectKey($projectId);
    }
    public static function getPortfolioRedirectLink($projectId)
    {
        return self::FRONTEND_DOMAIN. '/' . self::SLUG_PORTFOLIO . '/?projectID="' . $projectId;
    }

    public static function getLeadSkillShortLink($projectId)
    {
        return self::SHORT_LINK.'/' . self::SLUG_SKILL . '/' . self::getProjectKey($projectId);
    }

    public static function getHomepageShortLink($projectId)
    {
        return self::SHORT_LINK.'/' . self::SLUG_HOMEPAGE . '/' . self::getProjectKey($projectId);
    }
    public static function getWebsiteLeadUrl($projectKey, $url = self::FRONTEND_DOMAIN)
    {
        return $url."?projectID=".$projectKey;
    }
    public static function getMeetingShortLink($projectId)
    {
        return self::SHORT_LINK.'/' . self::SLUG_MEET . '/' . self::getProjectKey($projectId);
    }
    public static function getMeetingRedirectLink($projectId)
    {
        return self::FRONTEND_MEETING_LINK."/?projectID=".$projectId;
    }

    public static function getTwitterLink($projectId = null) {
        return self::TWITTER_LINK;
    }

    public static function getLinkedinLink($projectId = null) {
        return self::LINKEDIN_LINK;
    }

    public static function getInstagramLink($projectId = null) {
        return self::INSTAGRAM_LINK;
    }

    public static function getFacebookLink($projectId = null) {
        return self::FACEBOOK_LINK;
    }

    /**
     * @param $projectId
     * @param null $leadSource
     * @return array
     */
    public static function getShortLinkList($projectId, $leadSource = null){

        $leadURL                = $leadSource;
        $meetingShortLink       = self::getMeetingShortLink($projectId);
        $whatsUpLink            = self::getWhatsAppShortLink($projectId);
        $leadShortLink          = self::getCrmLeadLink($projectId);
        $leadSkillShortLink     = self::getLeadSkillShortLink($projectId);
        $homeShortLink          = self::getHomepageShortLink($projectId);
        $communityLeadShortLink = self::getCrmCommunityLeadLink($projectId);
        $landingShortLink       = self::getLandingShortLink($projectId);
        $portfolioShortLink     = self::getPortfolioShortLink($projectId);

        $shortLinkList = [
            'WhatsApp Short Link'      => $whatsUpLink,
            'Lead Short Link'           => $leadShortLink,
            'Lead Original Link'        => $leadURL,
            'Lead Skill Short Link'     => $leadSkillShortLink,
            'Community Lead Short Link' => $communityLeadShortLink,
            'Homepage Short Link'       => $homeShortLink,
            'Meeting Short Link'        => $meetingShortLink,
            'Landing page Short Link'        => $landingShortLink,
            'Portfolio Short Link'      => $portfolioShortLink,
        ];
        return $shortLinkList;
    }
}