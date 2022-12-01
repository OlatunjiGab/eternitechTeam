<?php

namespace App\Jobs\SyncData;

use DB;
/*use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
*/

class LiveDBConnection 
{

	private static $_db = 'mysql_live_db';
    /**
     * Execute the job.
     *
     * @return void
     */
    public static function connection(){
        return DB::connection(self::$_db);
	    // Get table data from production
	    
	    /*foreach($live_database->table('table_name')->get() as $data){
	        // Save data to staging database - default db connection
	        DB::table('table_name')->insert((array) $data);
	    }*/
	    // Get table_2 data from production
	    /*foreach($live_database->table('table_2_name')->get() as $data){
	        // Save data to staging database - default db connection
	        DB::table('table_2_name')->insert((array) $data);
	    }*/
    }
    public static function disconnect(){
        DB::disconnect(self::$_db);
    }
}
