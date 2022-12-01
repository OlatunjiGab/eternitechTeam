<?php
namespace App\Channels\EmailParser;

use App\Models\Language;
use Sunra\PhpSimple\HtmlDomParser;
use App\Classes\WriteLog;
/**
 * Created by Gopal.
 *
 * Descriptions : Simple incoming mail parse 
 * 
 */

class NewEmailParser extends BaseParser {

	/*public function __construct(){
		parent::__construct();
	}*/

	/**
     * parseData : incoming email data parse 
     * 
     * @param $data mandrillObject
     * @return $response array
     */
    public function parseData($data):array{
        //print_r($data);
		//echo ltrim("..w..Hello....",'\t.' );
		//Email Parse Email bean class
		//$newEmail->_to = array_combine(array_keys($newEmail->_to), $data->msg->to[0]);
		$originalHtml = $this->emailScrape($data);  

        //html object for link serach
        /*preg_match(config('constant.parse.forwardFrom'), $originalHtml , $fromMatches, PREG_OFFSET_CAPTURE);
        if(!empty($fromMatches) && isset($fromMatches[0][1])){
	        $fromHtml = substr($originalHtml , $fromMatches[0][1] ). "\n";
	        $html = HtmlDomParser::str_get_html($fromHtml);
	    }else{
	    	$html = HtmlDomParser::str_get_html($originalHtml);
	    }*/

        //html object for text data
        preg_match($this->_forwardTo, $originalHtml , $toMatches, PREG_OFFSET_CAPTURE);
        //to:
        if(!empty($toMatches) && isset($toMatches[0][1])){
	        $brPostion = strpos($originalHtml, '</a>', $toMatches[0][1]);
	        $this->emailModel->html = substr($originalHtml , $brPostion ) . "\n";
            $this->emailModel->text = strip_tags($this->emailModel->html);
	    }
        //remove all 
        $this->removeSignature();
        $aAttachment = [];
         

        $response = array(
            'type' => Parser::DATA_TYPE_NEW_PROJECT,
            'email' => $this->emailModel->from['email'],
            'name'  => $this->emailModel->from['name'],
            'phone'  => $this->emailModel->phone,
            'subject'  => trim($this->emailModel->subject),
            'text'  => trim(ltrim(trim(str_replace(">","",str_replace("&gt;", "", $this->emailModel->text)),"\t."))),
            'attachment' => $this->_aAttachment,
            'language' => Language::detectLanguage($this->emailModel->subject)
        );
        return $response;
	}

	/**
     * removeSignature : remove signature and unnecessary thing from message 
     * 
     * @param none
     * @return none
     */
	protected function removeSignature(){
		br:
    	$brPostion = strpos($this->emailModel->html, '<div class="gmail_quote">');
        $text = $this->emailModel->html;
    	if(!empty($brPostion) || $brPostion!=""){
            $text = substr($this->emailModel->html,0 , $brPostion );
            $sPostion = strpos($text, 'class="gmail_signature"');
            if(!empty($sPostion) || $sPostion!=""){
                $text = substr($text,0 , $sPostion );
            }
            $sPostion = strpos($text, 'gmail_signature');
            if(!empty($sPostion) || $sPostion!=""){
                $text = substr($text,0 , $sPostion );
            }
            $this->emailModel->text = strip_tags($text);
            if(empty(($this->emailModel->text))){
                $this->emailModel->html = substr($this->emailModel->html, $brPostion + 25);
                goto br;
            }
        }else{
            $sPostion = strpos($text, 'class="gmail_signature"');
            if(!empty($sPostion) || $sPostion!=""){
                $text = substr($text,0 , $sPostion );
            }
            $sPostion = strpos($text, 'data-smartmail="gmail_signature"');
            if(!empty($sPostion) || $sPostion!=""){
                $text = substr($text,0 , $sPostion );
            }
            $this->emailModel->text = strip_tags($text);
        }
	}
}