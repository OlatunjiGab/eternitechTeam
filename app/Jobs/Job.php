<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Classes\WriteLog;

abstract class Job
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "onQueue" and "delay" queue helper methods.
    |
    */


    use Queueable;
    
    /**
     * @var string
     */
    protected $logFileName;

    /**
      * The job failed to process.
      *
      * @param  \Exception  $exception
      * @return void
      */
     public function failedLog(\Exception $exception){
         // Send user notification of failure, etc...
         $this->writeLog("JOB FAILED!!!");
         $this->writeLog($exception->getMessage());
         $this->writeLog($exception->getTraceAsString());
     }

    /**
     * @param $message
     * @param $clearLog
     */
    protected function writeLog(string $message, bool $clearLog = false){
        WriteLog::write($this->logFileName, $message, $clearLog);
    }
}
