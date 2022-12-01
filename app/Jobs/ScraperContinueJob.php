<?php

namespace App\Jobs;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Curl;
use App\Helpers\SystemResponse;
use App\Models\Language;
use App\Models\Project;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Job;

class ScraperContinueJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    public $jobKey;
    public $value;

    /**
     * @var string
     */
    public $queue = 'get-leads';

    /**
     * FetchLeadsJob constructor.
     *
     * @param      $channel
     * @param null $keyword
     */

    public function __construct($jobKey, $value)
    {
        $this->jobKey = $jobKey;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $cacheJob = Cache::get($this->jobKey);
            if ($cacheJob) {
                $channelObj = BaseChannel::getChannel($cacheJob['channel']);

                if (!$channelObj) {
                    throw new \Exception('channel ' . $channelObj->channel . ' couldn\'t be found');
                }

                $url = $cacheJob['url'];
                $params = $cacheJob['params'];
                $params['continue'] = self::prepareContinuePayload($cacheJob['response'], $this->value);
                $channelObj->scraperCall($url, $params);
            }
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
        }
    }

    public static function prepareContinuePayload($originalResponse, $value)
    {
        if (!$originalResponse) {
            return [];
        }

        return [
            'code'    => SystemResponse::getFailCode($originalResponse),
            'context' => SystemResponse::getContext($originalResponse),
            'value'   => $value
        ];
    }
}