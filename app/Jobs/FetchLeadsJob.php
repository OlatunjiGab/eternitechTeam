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

class FetchLeadsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    public $channel;
    public $keyword;

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

    public function __construct($channel, $keyword = null)
    {
        $this->channel = $channel;
        $this->keyword = $keyword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $channelObj = BaseChannel::getChannel($this->channel);
            if (!$channelObj) {
                throw new \Exception('channel ' . $this->channel . ' couldn\'t be found');
            }
            $parseData = $channelObj->fetchNewProjects($this->keyword);
            $newProjectsCount = 0;

            $bidOnLeads = [];
            if (SystemResponse::isSuccess($parseData)) {
                if (!empty(SystemResponse::getData($parseData))) {
                    foreach (SystemResponse::getData($parseData) as $key => $pData) {
                        $description = $pData['description'];
                        preg_match_all('@[^a-zA-Z]{7,}@is',$description,$phoneMatches);
                        $phoneMatches=$phoneMatches[0];
                        foreach ($phoneMatches as $key => $match) {
                            $phoneMatches[$key]=preg_replace('@[^0-9]@s','',$match);
                        }

                        $phone = "";
                        if(count($phoneMatches)){
                            $phone = $phoneMatches[0];
                        }

                        preg_match_all("/[._a-zA-Z0-9-]+@[._a-zA-Z0-9-]+/i", $description, $emailMatches);
                        $email = "";
                        if(count($emailMatches) > 0 && count($emailMatches[0])) {
                            $email = $emailMatches[0][0];
                        }

                        $aData = array(
                            'client'     => $pData['client_name'],
                            'phone'      => $phone,
                            'email'      => $email,
                            'subject'    => $pData['title'],
                            'text'       => $pData['description'],
                            'attachment' => "",
                            'skill'      => $pData['skills'] ?? '',
                            'categories' => $pData['categories'],
                            'url'        => $pData['url'],
                            'is_hourly'  => $pData['is_hourly'] ?? 1,
                            'source'     => Project::SOURCE_AUTOMATED,
                            'language'   => Language::detectLanguage((string) $pData['title']),
                            'channel'    => $this->channel
                        );

                        // insert data into our database
                        $response = Project::createNewProject($aData, true);
                        if (SystemResponse::isSuccess($response)) {
                            $newProjectsCount++;
                            if (SystemResponse::getDataAttribute($response, 'bid')) {
                                $bidOnLeads[] = SystemResponse::getDataAttribute($response, 'projectId');
                            }
                        }
                    }
                    $response = SystemResponse::build(true, array('message' => 'Project Created Successfully...'));
                } else {
                    $response = SystemResponse::build(false,
                        array('message' => 'Projects data was not received correctly...'));
                }
            } else {
                $response = SystemResponse::build(false, array('message' => 'Response code failed...'));
            }

            // if any project scraped
            if (count(SystemResponse::getData($parseData)) > 0) {
                Slack::send(new LogJob(true,
                    $newProjectsCount . '/' . count(SystemResponse::getData($parseData)) . ' Projects added, bidding on ' . count($bidOnLeads) . ' [' . $this->keyword . ']',
                    array('channel' => $this->channel)));
            }

            if (!empty($bidOnLeads)) {
                dispatch(new BidLeadJob($bidOnLeads));
            }

            return $response;
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
        }
    }
}