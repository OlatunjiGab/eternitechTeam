<?php

namespace App\Jobs;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Curl;
use App\Models\Language;
use App\Models\Project;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Job;

class BidLeadJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    public $projectIds;

    /**
     * @var string
     */

    /**
     * @var string
     */
    public $queue = 'get-leads'; // temporary based on this queue as the scraper server cannot hold it.

    /**
     * BidLeadJob constructor.
     *
     * @param $projectIds
     */

    public function __construct($projectIds)
    {
        $this->projectIds = is_array($projectIds) ? $projectIds : [$projectIds];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $projects = Project::whereIn('id', $this->projectIds)->get();
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    if($project->channel != BaseChannel::CRAIGSLIST){
                    $project->bid();
                    }
                }
            } else {
                Slack::send('Cannot find projects: ' . implode(',', $this->projectIds));
            }

        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
        }
    }
}