<?php

namespace App\Console\Commands;

use App\Jobs\LeadsTranslateJob;
use App\Http\Controllers\LA\DashboardController;
use Illuminate\Console\Command;

class LeadsTranslateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads-translate {--local}';

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
        $isLocal = $this->option('local');
        $job = new LeadsTranslateJob();
        if ($isLocal) {
            $job->handle();
        } else {
            dispatch($job);    
        }
    }
}
