<?php

namespace App\Console\Commands;

use App\Channels\BaseChannel;
use App\Jobs\FetchLeadsJob;
use Illuminate\Console\Command;
use App\Http\Controllers\IncomingController;
use App\Classes\WriteLog;


class FetchLeadsCommand extends Command
{
    protected $_logFile = 'Inbound/craigslist-log.txt';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetchLeads {channel} {--local}';

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
        $channel = $this->argument('channel') ?? BaseChannel::XPLACE;
        $isLocal = $this->option('local');

        $mytime = \Carbon\Carbon::now();
        WriteLog::write($this->_logFile,
            "fetchLeadsJob start Datetime " . print_r($mytime->toDateTimeString(), true) . " end \r\n");


        $channelObj = BaseChannel::getChannel($channel);
        if (!$channelObj) {
            throw new \Exception('channel ' . $channel . ' couldn\'t be found');
        }

        $channelObj->dispatchNewProjectsJobs($isLocal);

        WriteLog::write($this->_logFile,
            " fetchLeadsJob end Datetime " . print_r($mytime->toDateTimeString(), true) . " end \r\n");
        $this->info('fetchLeadsJob Run successfully by Developer!');
    }
}
