<?php

namespace App\Console;

use App\Channels\BaseChannel;
use App\Console\Commands\AutomatedFollowupCommand;
use App\Jobs\FetchLeadsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Mail;
use App\Console\Commands\LeadsTranslateCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\FetchLeadsCommand::class,
        Commands\Inspire::class,
        Commands\SyncData\MigrateDataCommand::class,
        Commands\BidLeadCommand::class,
        Commands\DeployCommand::class,
        Commands\AutomatedFollowupCommand::class,
        Commands\LeadsTranslateCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // only automate on production
        if (env('APP_ENV') == 'production') {
            // get new leads
            $schedule->call(function () {
                $this->fetchProjects();
            })->cron('0 */12 * * *'); // every 12 hours

            //$schedule->command('automated-message')->cron('0 */1 * * *'); // every hour
//            $schedule->command('automated-message')->cron('*/10 * * * *'); //every 10 minutes
            //$schedule->command('leads-translate')->cron('0 */12 * * *'); // every 12 hours
//            $schedule->command('leads-translate')->cron('20 */12 * * *'); // every 12 hours & 20 mins
        }
    }


    public function fetchProjects()
    {
        // adding them to queue to process
        $allChannels = array_flip(BaseChannel::getAllChannels());
        unset($allChannels[BaseChannel::EMAIL]);
        unset($allChannels[BaseChannel::CRM]);
        unset($allChannels[BaseChannel::NONE]);
        // temporary
        unset($allChannels[BaseChannel::FREELANCER]);
        $allChannels = array_flip($allChannels);
        foreach ($allChannels as $channelKey) {
            $channel = BaseChannel::getChannel($channelKey);
            $channel->dispatchNewProjectsJobs();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
//        $this->load(__DIR__.'/Commands');
     
    //    require base_path('Routes/console.php');
    }

}
