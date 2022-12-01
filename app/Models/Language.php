<?php

namespace App\Models;

use App\Classes\Cache;
use DetectLanguage\DetectLanguage;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $guarded = [];

    public $timestamps = false;


    const ENGLISH_CODE = 'en';
    const HEBREW_CODE = 'he';

    const DEFAULT = self::ENGLISH_CODE;
    const CACHE_ALLOWED_KEY = 'cache_allowed_languages';
    const CACHE_LANGUAGE_LISTS = 'cache_language_lists';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_LIST = [
        self::STATUS_ACTIVE => "Active",
        self::STATUS_INACTIVE => "Inactive",
    ];

    const RTL_LANGUAGES = [self::HEBREW_CODE];

    const CACHE_PREFIX = 'language_';

    public static function getDefault() {
        return self::DEFAULT;
    }

    public static function getAllowedLanguageCodes() {
        return \Cache::get(self::CACHE_ALLOWED_KEY, function() {
            return self::where('status', '=',true)->pluck('code')->toArray();
        });
    }

    public static function isAllowed($languageCode) {
        return in_array($languageCode, self::getAllowedLanguageCodes());
    }

    public static function getCachedMatchingLangauge($string) {
        $cacheKey = self::CACHE_PREFIX . $string;
        return Cache::get($cacheKey, function() use ($string) {
            return self::getMatchingLanguage($string);
        }, false, Cache::TIMEOUT_1MONTH);
    }

    public static function getMatchingLanguage($string)
    {
        DetectLanguage::setApiKey(env('DETECT_LANGUAGE_API_KEY', ''));

        $result = DetectLanguage::detect($string);

        $code = null;
        if (!empty($result)) {
            $language = current($result);

            $code = $language->language;

            switch ($code) {
                case 'iw':
                    $code = self::HEBREW_CODE;
                    break;
                default:
                    break;
            }

            $code = ($language->isReliable) ? $code : null;
        }

        return $code;
    }

    /**
     * detectLanguage : detect project title language
     *
     * @param $string string
     * @return $language string
     */
    public static function detectLanguage(string $string) : string{
        $code = self::getMatchingLanguage($string);

        return (!empty($code) && self::isAllowed($code)) ? $code : self::getDefault();
    }

    public static function getLanguageList() {
        return \Cache::get(self::CACHE_LANGUAGE_LISTS, function() {
            return self::select('name','code')->where('status', '=',true)->pluck('name','code')->toArray();
        });
    }

    public static function getLanguageNameByCode($languageCode) {
        $languageName = $languageCode;
        if($language = self::select('name')->where('code','=',$languageName)->first())
        {
            $languageName =$language->name;
        }
        return $languageName;
    }

    public static function filterByLanguage($list, $language = self::DEFAULT) {
        $filteredList = array();
        foreach ($list as $phrase) {
            $code = self::getCachedMatchingLangauge($phrase);
            if ($code == $language) {
                $filteredList[] = $phrase;
            }
        }
        return $filteredList;
    }

    public static function isRTL ($languageCode) {
        return in_array($languageCode, self::RTL_LANGUAGES);
    }
}
