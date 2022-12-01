<?php

namespace App\Helpers;

use App\Models\ProjectMessage;
use Exception;
use Illuminate\Http\Request;
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
class VisitorDetails
{
    public $ua;
    public $ip;
    public $city        = null;
    public $country     = null;
    public $countryCode = null;
    public $browserName;
    public $browserVersion;
    public $osName;
    public $osVersion;
    public $deviceType;
    public $isBot;

    public function __construct(Request $request)
    {
        if ($request) {
            $this->loadData($request);
        }
    }

    public function loadData(Request $request)
    {
        $this->ip = $request->getClientIp();
        $ipInfo = VisitorDetails::getUserInfoByIp($this->ip);

        $this->ua = $request->header('User-Agent');
        $browser = get_browser($this->ua, true);

        if (!empty($ipInfo)) {
            $this->city = $ipInfo->city;
            $this->country = $ipInfo->country;
            $this->countryCode = strtolower($ipInfo->countryCode);
        }

        $this->browserName = $browser['browser'];
        $this->browserVersion = $browser['version'];
        $this->osName = trim(preg_replace("/[0-9\.]/", '', $browser['platform']));
        $this->osVersion = preg_replace("/[^0-9\.]/", '', $browser['platform']);
        $this->deviceType = $browser['device_type'];

        $this->isBot = self::detectIfBot($this->ua);
    }

    /**
     * get os font-awesome icon html using $osName
     *
     * @param string $osName
     *
     * @return string
     */
    public static function getOsIcon($osName = "Windows")
    {
        switch ($osName) {
            case "Windows":
            case "Win":
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

    /**
     * get browser font-awesome icon html using $browserName
     *
     * @param string $browserName
     *
     * @return string
     */
    public static function getBrowserIcon($browserName = "Chrome")
    {
        switch ($browserName) {
            case "Chrome":
                $browserIcon = "fa-chrome";
                break;
            case "Mozilla Firefox":
            case "Firefox":
                $browserIcon = "fa-firefox";
                break;
            case "Safari":
                $browserIcon = "fa-safari";
                break;
            case "Opera":
                $browserIcon = "fa-opera";
                break;
            case "Microsoft Legacy Edge":
            case "Microsoft Edge":
                $browserIcon = "fa-edge";
                break;
            case "Microsoft Internet Explorer":
                $browserIcon = "fa-internet-explorer";
                break;
            default:
                $browserIcon = "fa-chrome";
        }
        $browserIconString = "<i class='fa $browserIcon' aria-hidden='true'></i>";

        return $browserIconString;
    }

    /**
     * get Country flag icon html
     *
     * @param string $countryCode
     *
     * @return string
     */
    public static function getCountryFlag($countryCode = 'us')
    {
        $countryFlagString = "<span class='flag-icon flag-icon-$countryCode'></span>";

        return $countryFlagString;
    }

    /**
     * get Device type icon html
     *
     * @param string $deviceType
     *
     * @return string
     */
    public static function getDeviceTypeIcon($deviceType = 'desktop')
    {
        $deviceType = strtolower($deviceType);
        $deviceTypeIconString = "<i class='fa fa-$deviceType' aria-hidden='true'></i>";

        return $deviceTypeIconString;
    }

    /**
     * get userinfo based on IP Address
     *
     * @param $ip
     *
     * @return false|mixed|object|string
     */
    public static function getUserInfoByIp($ip)
    {
        $ipInfo = (object)[];
        if ($ip && $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip)) {
            $ipInfo = json_decode($ipInfo);
        }

        return $ipInfo;
    }

    public static function getUserAgentInfoHtml($userAgentInfo)
    {
        $ip = $userAgentInfo->ip_address;

        $city = $userAgentInfo->city;
        $country = $userAgentInfo->country;
        $countryCode = $userAgentInfo->country_code;
        $countryFlagString = "";
        if ($countryCode) {
            $countryFlagString = self::getCountryFlag($countryCode);
        }

        $browserName = $userAgentInfo->browser_name;
        $browserIconString = null;
        if ($browserName) {
            $browserIconString = self::getBrowserIcon($browserName);
        }

        if ($browserVersion = $userAgentInfo->browser_version) {
            $browserName = $browserName . "(" . $browserVersion . ")";
        }

        $osName = $userAgentInfo->os_name;
        $osIconString = "";
        if ($osName) {
            $osIconString = self::getOsIcon($osName);
        }

        if ($osVersion = $userAgentInfo->os_version) {
            $osName = $osName . "(" . $osVersion . ")";
        }

        $deviceType = $userAgentInfo->device_type;
        $deviceTypeIconString = null;
        if ($deviceType) {
            $deviceTypeIconString = self::getDeviceTypeIcon($deviceType);
        }

        $userAgentInfoString = $ip . " " . $country . " " . $countryFlagString . " " . $city . " " . $browserIconString . " " . $browserName  . " " . $osIconString . " " . $osName . " " . $deviceTypeIconString . " " . $deviceType;
        $locationInfoString = $ip . " " . $country . " " . $countryFlagString . " " . $city;
        $deviceInfoString = " " . $browserIconString . " " .  $browserName . " " . $osIconString . " " . $osName . " " . $deviceTypeIconString . " " . $deviceType;

        $countryString = $countryFlagString ? : $country;
        $countryString = $countryString ? : $ip;
        $locationInfoStringHtml = '<span class="timeline-country-popover" data-toggle="popover" title="User Location Info" data-content="' . $locationInfoString . '">' . $countryString . ' ' . $city . '</span> ';
        $deviceInfoStringHtml = ' <span class="timeline-content-popover" data-toggle="popover" title="UserAgent Info" data-content="' . $userAgentInfoString . '">' . $deviceInfoString . '</span>';
        $userAgentInfoHtmlString['location_device_info'] = $locationInfoStringHtml . " " . $deviceInfoStringHtml;
        $userAgentInfoHtmlString['full_user_agent_info'] = $userAgentInfoString;

        return $userAgentInfoHtmlString;
    }

    public static function isMobileDevice()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
			|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
            , $_SERVER["HTTP_USER_AGENT"]);
    }

    protected static function detectIfBot($userAgent) {
        return (
            isset($userAgent)
            && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent)
        );
    }
}