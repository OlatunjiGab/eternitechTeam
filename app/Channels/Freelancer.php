<?php

namespace App\Channels;

use App\Classes\Slack;
use App\Helpers\CommonUtils;
use App\Helpers\Curl;
use App\Helpers\Message;
use App\Models\ProjectMessage;
use App\Notifications\LogJob;
use Mail;

/**
 * Auto-Bidding Base class
 */
class Freelancer extends BaseChannel
{
    const CURRENCY = 'USD';

    public function getCurrency()
    {
        return self::CURRENCY;
    }

    public function getDefaultMessageChannel()
    {
        return Message::CHANNEL_SCRAPER;
    }

    public function isBidAvailable() {
        return true;
    }

    public function getBotEventType () {
        return ProjectMessage::EVENT_TYPE_VIEWED;
    }
}