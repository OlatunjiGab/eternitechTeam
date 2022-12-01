<?php

namespace App\Jobs\SyncData;

use App\Jobs\Job;
use App\Classes\WriteLog;
use DB;
//use Config;
/*use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
*/

abstract class BaseMigrationDB extends Job // implements ShouldQueue
{
    //use InteractsWithQueue, SerializesModels;
    /**
     * @var string
     */
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param Merchant $merchant
     * @param string $caller (to be used used for debugging) Possible values: Artisan, Queue, Scheduler_auto, Scheduler_custom
     */
    public function __construct(string $dbTableName = 'all')
    {
        $this->logFileName = 'SyncDBLog/migration_db.txt';//base_path("storage/logs");
        $this->type = $dbTableName;
    }

    /**
     * make connection with old DB 
     * 
     * @return DB:connection object
     */

    public function connect(){
        //echo DB::connection()->getDatabaseName();//die;
        /*Config::set('database.connections.foo', [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'crm_old',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);*/
        return LiveDBConnection::connection();
    }
    public function disconnect(){
        LiveDBConnection::disconnect();
    }
  }
