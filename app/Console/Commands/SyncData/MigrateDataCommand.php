<?php

namespace App\Console\Commands\SyncData;

use Illuminate\Console\Command;
use App\Jobs\SyncData\MigrationDBJob;

class MigrateDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string option:table-name/all
     */
    protected $signature = 'sync:db {table} {--local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchroniz Old-DB data in New-DB';

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
    public function handle(){

        $isLocal = $this->option('local');
        $job = new MigrationDBJob($this->argument('table'));
        $job->handle();                
    
    }
}
