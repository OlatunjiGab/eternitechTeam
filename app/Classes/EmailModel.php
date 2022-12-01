<?php
namespace App\Classes;

/**
 * Created by Gopal.
 *
 * Descriptions : parse email bean class 
 * 
 */

class EmailModel {

    public $from       = ["email" =>'', "name" =>''];
    public $to         = ["email" =>'', "name" =>''];
    public $html       = "";
    public $subject    = "";
    public $headers    = [];
    public $text       = "";
    public $timeStamp  = "";
    public $projectURL = "";
    public $parsedHTML;
    public $phone      = "";
    public $action     = "";

} 