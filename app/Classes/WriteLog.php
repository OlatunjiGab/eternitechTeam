<?php
namespace App\Classes;

use Carbon\Carbon;
use Storage;
use DB;

class WriteLog {

    const OWNER = 'ec2-user';
    /**
     *  Write Log with this function  
     *
     *  @param $file        string  
     *  @param $message     string
     *  @param $clearLog   boolen    
     */
    public static function write(string $file, string $message, bool $clearLog = false)
    {
      self::checkDisk($file);
      $file = storage_path('logs/'.$file);
      $content = Carbon::Now()->toDateTimeString() . ": " . $message . "\r\n";
      if($clearLog){
        file_put_contents($file, $content);
      }else{
        file_put_contents($file, $content, FILE_APPEND);
      }
      //chown($file, self::OWNER);
      //$live_database = \DB::connection('mysql_live_db');
    }
    /**
     *  Check directory exist or not 
     *
     *  @param $file string   
     */
    public static function checkDisk($file){
      $dir = pathinfo($file)['dirname'];
      if(!is_dir($dir)){
        Storage::disk('logs')->makeDirectory($dir);
      }
    }
}
