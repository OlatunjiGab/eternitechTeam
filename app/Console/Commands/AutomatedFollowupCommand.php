<?php

namespace App\Console\Commands;

use App\Jobs\BidLeadJob;
use App\Models\Project;
use Illuminate\Console\Command;
use App\Http\Controllers\IncomingController;
use App\Classes\WriteLog;


class AutomatedFollowupCommand extends Command
{
    protected $_logFile = 'Inbound/craigslist-log.txt';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:bid {projectId} {--local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $projectId = $this->argument('projectId');
//        $isLocal = $this->option('local');
//        $job = new BidLeadJob($projectId);
//        if ($isLocal) {
//            $job->handle();
//        } else {
//            dispatch($job);
//        }

    }
}
