<?php

namespace App\Jobs\SyncData;

/*use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
*/
use DB;

class MigrationDBJob  extends BaseMigrationDB 
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $this->writeLog("Start Migration DB");
        $this->_sync();
        $this->writeLog("END Migration DB");
    }
    /**
     * Data Sync and migrate in NEW DB
     *
     * @return void
     */
    private function _sync(){
        /*echo $this->connect()->getDatabaseName();
        $aTableData = collect($this->connect()->table('companies')->get())->map(function($x){ return array_map('trim', (array) $x); })->toArray();
        print_r($aTableData);
        die;*/
        $tables = config('constant.old_db_migration.tables');
        switch (trim($this->type)) {
            case 'all':
                $tableLoop = $tables;
                break;
            default:
                if($tables[trim($this->type)]){
                    $tableLoop = $tables[trim($this->type)];
                }else{
                    $tableLoop = '';
                }
                break;
        }
        if(!empty($tableLoop)){
            if(is_array($tableLoop)){
                foreach ($tableLoop as $newTable=>$oldTable) {
                    $this->_insertData($newTable,$oldTable);
                }
            }else{
                $this->_insertData(trim($this->type),trim($tableLoop));
            }
        }
        $this->disconnect();
    }
    /**
     * Insert DATA from export table to migrate table
     * 
     * @param string $migrationTB   "a table name in which we need to migrate old data"
     * @param string $exportTB      "a table name where from we get data"
     * @return void
     */
    private function _insertData($migrationTB,$exportTB){
        try{
            $column = [];
            foreach (config('constant.old_db_migration.column.'.$migrationTB) as $key => $value) {
                $column[] = DB::raw($value);
            }
            switch ($migrationTB) {
                case 'companies':
                case 'skills':
                case 'projects';
                case 'project_messages';
                case 'project_companies';
                case 'project_skills':
                case 'supplier_skills':
                    $aTableData = collect($this->connect()->table($exportTB)->select($column)->get())->map(function($x){ return array_map('trim', (array) $x); })->toArray();
                    break;
                
                    /*$aTableData = collect($this->connect()->table($exportTB)->select($column)->join('project_companies', $exportTB.'.id', '=', 'project_companies.project_id')->get())->map(function($x){ return array_map('trim', (array) $x); })->toArray();
                    break;*/
                case 'company_contacts':
                case 'suppliers';
                    $aTableData = collect($this->connect()->table($exportTB)->select($column)->join('companies', $exportTB.'.company_id', '=', 'companies.id')->groupBy('companies.id')->get())->map(function($x){ return array_map('trim', (array) $x); })->toArray();
                    /*if(!empty($aTableData)){
                        //DB::table($migrationTB)->delete();
                        DB::table('companies')->truncate();
                        foreach($aTableData as $saveData){
                            DB::table($migrationTB)->insert($saveData);
                        }
                    }*/
                    break;
                /*case 'project_skills':
                case 'supplier_skills':
                    $aTableData = collect($this->connect()->table($exportTB)->select($column)->get())->map(function($x){ return array_map('trim', (array) $x); })->toArray();
                    if(!empty($aTableData)){
                        DB::table($migrationTB)->truncate();
                        foreach($aTableData as $saveData){
                            DB::table($migrationTB)->insert($saveData);
                        }
                    }
                    break;*/
                default:
                    break;
            }
            if(!empty($aTableData)){
                //DB::table($migrationTB)->delete();
                DB::table($migrationTB)->truncate();
                foreach($aTableData as $saveData){
                    if($migrationTB=='supplier_skills'){
                        $supplierId = DB::table('suppliers')->where('company_id',$saveData['company_id'])->first()->id;
                        unset($saveData['company_id']);
                        $saveData['supplier_id'] = $supplierId;
                    }
                    DB::table($migrationTB)->insert($saveData);
                }
            }
        }catch(Exception $e){
            
        }
    }
}
