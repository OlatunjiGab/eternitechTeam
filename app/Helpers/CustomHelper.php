<?php
namespace App\Helpers;
use App\Models\AutomatedMessage;
use Auth;
use DB;
use Dwij\Laraadmin\Helpers\LAHelper;
use Mail;
use App\Models\ProjectMessage;

use Dwij\Laraadmin\Models\LAConfigs;
use Illuminate\Support\HtmlString;
use phpDocumentor\Reflection\Types\Self_;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use App\Helpers\ShortLink;

class CustomHelper
{
    const HOURS = 1;
    const DAYS = 2;
    const MONTHS = 3;
    const YEARS = 4;
    const SECONDS = 5;
    const MINUTES = 6;

    /**
    * developed by : Pavan sharma
    * function for : Table listing 
    * @return array
    */
    public static function getAllData($sTableName,$aSelect,$aCondition='',$sLimit='')
    {
        if (empty($aSelect)) {
           $aSelect=' * ';
        }

        if (empty($sLimit)) {
            $sLimit='100';
        }

        $query = DB::table($sTableName)->select($aSelect);       
        if (!empty($aCondition)) {
            $query = $query->where(function ($qry1) use($aCondition) {
                return $qry1->where($aCondition);
            });
        }
        $aRowData = $query->orderBy('created_at','desc')->limit($sLimit)->get();
        return $aRowData->toArray();
    }

    /**
    * developed by : Pavan sharma
    * function for : Get data for drop down 
    * @return array
    */
    public static function getAllDataForDropDown($sTableName,$aCondition='',$aOrderBy='',$sLimit='')
    {
        if (empty($aSelect)) {
           $aSelect=' * ';
        }
        if (empty($sLimit)) {
            $sLimit='100';
        }     

        $query = DB::table($sTableName);       
        if (!empty($aCondition)) {        
            $query = $query->where(function ($qry1) use($aCondition){
                return $qry1->where($aCondition);
            });
        }

        if (!empty($aOrderBy)) {
            foreach ($aOrderBy as $orderBykey => $orderByName) {                
                $query->orderBy($orderBykey, $orderByName);
            }
        }

        if (!empty($sLimit)) {
            $query->limit($sLimit);
        }

        $aRowData = $query->pluck('name','id');
        return $aRowData->toArray();
    }


    /**
    * developed by : Pavan sharma
    * function for : Table single row 
    * @return array
    */
    public static function singleData($sTableName,$aSelect='',$aCondition='')
    {
        if (empty($aSelect)) {
           $aSelect=$sTableName.'.*';
        }
        $query = DB::table($sTableName)->select($aSelect);       
        if (!empty($aCondition)) {        
            $query = $query->where(function ($qry1) use($aCondition){
                return $qry1->where($aCondition);
            });
        }
        $aRowData = $query->first();
        if (isset($aRowData) && !empty($aRowData)) {     
     
            return $aRowData;
        }
    }
    


    public static function makeSlug($name,$sTableName) {
        if ($name) {          
            $slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
            $aRowData = DB::table($sTableName)->select('slug')->where(['slug'=>$slug])->first();       
            if (empty($aRowData)) {
                return $slug;
            } else {
                return $slug.'-'.rand(10,100);
            }
            
        }
    }

    
    public static function test()
    {
        echo "hello";
    }

    public static function countDays($start,$end) {
        $startTimeStamp = strtotime($start);
        $endTimeStamp = strtotime($end);
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $numberDays = $timeDiff/86400;
        return $numberDays;
    }

    /**
     * @param int $type
     * @param int $value
     * @return false|int|string
     */
    public static function getDays($type = self::DAYS, $value)
    {
        $value = (int) $value;
        $end = date('Y-m-d');
        if($type == self::DAYS) {
            $start = date('Y-m-d', strtotime("-$value days"));
            return  self::countDays($start,$end);
        } elseif ($type == self::MONTHS) {
            $start = date('Y-m-d', strtotime("-$value months"));
            return  self::countDays($start,$end);
        } elseif ($type == self::YEARS) {
            $start = date('Y-m-d', strtotime("-$value years"));
            return  self::countDays($start,$end);
        } else {
            return 1;
        }
    }

    public static function getLastNDates($days, $format = 'Y-m-d') {
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y));
        }
        return array_reverse($dateArray);
    }

    public static function printMenuEditor($menu) {
        $editing = \Collective\Html\FormFacade::open(['url' => url(config("laraadmin.adminRoute") ."/la_menus", [$menu->id]), 'method' => 'delete', 'style'=>'display:inline']);
        $editing .= '<button class="btn btn-xs btn-danger pull-right"><i class="fa fa-times"></i></button>';
        $editing .= \Collective\Html\FormFacade::close();
        if($menu->type != "module") {
            $info = (object) array();
            $info->id = $menu->id;
            $info->name = $menu->name;
            $info->url = $menu->url;
            $info->type = $menu->type;
            $info->icon = $menu->icon;

            $editing .= '<a class="editMenuBtn btn btn-xs btn-success pull-right" info=\''.json_encode($info).'\'><i class="fa fa-edit"></i></a>';
        }
        $str = '<li class="dd-item dd3-item" data-id="'.$menu->id.'">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content"><i class="fa '.$menu->icon.'"></i> '.$menu->name.' '.$editing.'</div>';

        $childrens = \Dwij\Laraadmin\Models\Menu::where("parent", $menu->id)->orderBy('hierarchy', 'asc')->get();

        if(count($childrens) > 0) {
            $str .= '<ol class="dd-list">';
            foreach($childrens as $children) {
                $str .= self::printMenuEditor($children);
            }
            $str .= '</ol>';
        }
        $str .= '</li>';
        return $str;
    }

    /**
     * check if Email is received or bounced then update flag in companies table
     * @param $emailId
     * @param $companyId
     * @return false|mixed|object|string
     */
    public static function checkAndUpdateBounceFlagEmail($emailId, $companyId) {
        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
        $request_headers = ["Accept: application/json"];

        $data = [];
        try {
            //$response = $sg->client->suppression()->bounces()->get(null, null, $request_headers);
            $response = $sg->client->suppression()->bounces()->_($emailId)->get();
            //print_r($response->headers());
            if (!empty($response)) {
                $data['statusCode'] = $response->statusCode();
                $data['body'] = $response->body();
                if (!empty($response->body())) {
                    $company = DB::table('companies')
                        ->where('id', $companyId)
                        ->update(['is_email_bounce'=>1]);

                    return true;
                }
                return false;
            } else {
                $data['statusCode'] = 202;
                $data['body'] = 'Error';
                return false;
            }
        } catch (Exception $ex) {
            //echo 'Caught exception: '.  $ex->getMessage();
            $data['statusCode'] = 201;
            $data['body'] = 'Caught exception: '.  $ex->getMessage();
            return false;
        }
    }

    /**
     * check if Email is received or bounced then update flag in suppliers table
     * @param $emailId
     * @param $supplierId
     * @return false|mixed|object|string
     */
    public static function checkAndUpdateBounceFlagEmailSupplier($emailId, $supplierId) {
        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
        $request_headers = ["Accept: application/json"];

        $data = [];
        try {
            //$response = $sg->client->suppression()->bounces()->get(null, null, $request_headers);
            $response = $sg->client->suppression()->bounces()->_($emailId)->get();
            //print_r($response->headers());
            if (!empty($response)) {
                $data['statusCode'] = $response->statusCode();
                $data['body'] = $response->body();
                if (!empty($response->body())) {
                    $supplier = DB::table('suppliers')
                        ->where('id', $supplierId)
                        ->update(['is_email_bounce'=>1]);

                    return true;
                }
                return false;
            } else {
                $data['statusCode'] = 202;
                $data['body'] = 'Error';
                return false;
            }
        } catch (Exception $ex) {
            //echo 'Caught exception: '.  $ex->getMessage();
            $data['statusCode'] = 201;
            $data['body'] = 'Caught exception: '.  $ex->getMessage();
            return false;
        }
    }
}