<?php 
namespace App\Channels;

use App\Classes\Slack;
use App\Helpers\CommonUtils;
use App\Helpers\Curl;
use App\Helpers\Message;
use App\Jobs\FetchLeadsJob;
use App\Models\Language;
use App\Models\Skill;
use App\Notifications\LogJob;
use Mail;

/**
* Auto-Bidding Base class 
*/

class Craigslist extends BaseChannel
{
    const CURRENCY = 'USD';

    public function getCurrency() {
        return self::CURRENCY;
    }

    public function getDefaultMessageChannel()
    {
        return Message::CHANNEL_EMAIL;
    }

    public function isBidAvailable() {
        // craigslist sent bid as message
        return false;
    }

    public function dispatchNewProjectsJobs($isLocal = false)
    {
        $skills = Skill::getAllSkills();
        foreach ($skills as $skill) {
            // only process technologies
            if ($skill->isTechnology) {
                $keywords = $skill->getKeywordsArray();
                $keywords = Language::filterByLanguage($keywords, Language::ENGLISH_CODE);
                foreach ($keywords as $keyword) {
                    if (!empty($keyword)) {
                        $searchKeyword = $this->getSearchKeyword($keyword);
                        $job = new FetchLeadsJob($this->channel, $searchKeyword);
                        if ($isLocal) {
                            $job->handle();
                        } else {
                            dispatch($job);
                        }
                    }
                }
            }
        }
    }
}