<?php

namespace App\Channels\EmailParser;

use Sunra\PhpSimple\HtmlDomParser;
use App\Classes\WriteLog;
use App\Classes\EmailModel;

/**
 * Created by Gopal.
 *
 * Descriptions : Incoming mail parse in appropiate way and save
 *
 */
class BaseParser
{

    const PARSER = 'unknown';
    /**
     * Member variable
     */

    /**
     * xplace project url : we have to find that pattern url from coming email
     */
    protected $_xplaceProjectUrl = ['www.xplace.com/job/', 'www.xplace.com?p'];

    /**
     * phone regular expresion
     */
    protected $_phoneRegex = '/\b[0-9]{3}\s*[-]?\s*[0-9]{3}\s*[-]?\s*[0-9]{4}\b/';
    /**
     * forward to pattern
     */
    protected $_forwardTo = '/To:|אל:/';
    /**
     * forward from pattern
     */
    protected $_forwardFrom = '/From:|מאת:/';
    /**
     * incoming mail is forwarded or not
     */
    protected $_isForwarded = false;
    /**
     * incoming logs path
     */
    protected $_upload = 'public/inbound/';
    /**
     * incoming email been class
     */
    protected $emailModel;
    /**
     * attachment array
     */
    protected $_aAttachment = [];
    /**
     * language detection API key
     */
    protected $_langApiKey = "717ef43887b4ad6d3b71a17b622b198e";

    public function __construct()
    {
        $this->emailModel = new EmailModel();
    }

    /**
     * parseData : Abstract methode , we are implementing in child class as per condition
     *
     * @param $data object
     */
    protected function parseData($data)
    {
        throw new \Exception('parser ' . self::PARSER . ' not implemented parseData function');
    }

    public static function detectParser($data)
    {
        throw new \Exception('parser ' . self::PARSER . '  not implemented getDefaultMessageChannel function');
    }

    /**
     * emailScrape function : scrape incoming email data
     *
     * @param $data object
     *
     * @return $html string
     */
    protected function emailScrape($data)
    {

        $this->emailModel->from['email'] = @$data["to"];
        $this->emailModel->from['name'] = (string)(isset($data["from"]) && !empty($data["from"]) ? $data["from"] : "");
        $this->emailModel->html = isset($data["html"]) && !empty($data["html"]) ? $data["html"] : '';
        $this->emailModel->subject = trim(str_replace(["Fwd:", "Re:"], ["", ""],
            $data["subject"]));//parsing the subject line
        $this->emailModel->text = isset($data["text"]) ? $data["text"] : '';
        $this->emailModel->timeStamp = isset($data["ts"]) ? $data["ts"] : '';

        $isForwarded = false;//strpos($data->msg->subject,"Fwd:");
        //Get Origin message
        //$this->getEmailMessage();
        $this->getEmailMessageSendgrid($this->emailModel);

        $originalHtml = HtmlDomParser::str_get_html($this->emailModel->html);

        //gets the phone number from the email
        preg_match_all(config("constant.parse.phoneRegex"), $originalHtml, $phoneMatches);
        if (!empty($phoneMatches) && isset($phoneMatches[0][0])) {
            $this->emailModel->phone = $phoneMatches[0][0];
        }
        //gets Sender email and name
        //$this->getEmailFrom($originalHtml); 
        $this->getEmailFromSendgrid($data);
        //fetch attachments
        //if(isset($data->msg->attachments))    
        //  $this->_aAttachment = $this->attachmentDownload($data->msg->attachments);
        return $originalHtml;
    }

    private function getEmailMessageSendgrid($emailModelData)
    {
        $getLastForward = strripos($emailModelData->html, "Forwarded message");
        if (!empty($getLastForward) || $getLastForward != "") {
            $this->_isForwarded = true;
            $this->emailModel->html = substr($emailModelData->html, $getLastForward);
        }
    }

    private function getEmailFromSendgrid($emailData)
    {
        $tmpSenderEmail = json_decode($emailData["envelope"], true);
        if ($tmpSenderEmail) {
            if (isset($tmpSenderEmail["from"]) && !empty($tmpSenderEmail["from"])) {
                $this->emailModel->from['email'] = $tmpSenderEmail["from"];
            }
        }
        if (isset($emailData['from'])) {
            $this->emailModel->from['name'] = $emailData['from'];
        }
    }

    /**
     * parseData : Abstract methode , we are implementing in child class as per condition
     *
     * @param $attachments object
     *
     * @return $attachment array
     */

    protected function attachmentDownload($attachments): array
    {
        $attachment = [];
        foreach ($attachments as $key => $value) {
            $attachment[] = $filename = $this->_upload . time() . $value->name;
            \Storage::put($filename, base64_decode($value->content));
        }

        return $attachment;
    }

    /**
     * getEmailMessage : get exact mail message, in case of forwarded condition
     *
     * @param none
     *
     * @return none
     */
    private function getEmailMessage()
    {
        $getLastForward = strripos($this->emailModel->html, "Forwarded message");
        if (!empty($getLastForward) || $getLastForward != "") {
            $this->_isForwarded = true;
            $this->emailModel->html = substr($this->emailModel->html, $getLastForward);
        }
    }

    /**
     * getEmailFrom : get original sender email and name form message
     *
     * @param $html Sunra\PhpSimple\HtmlDomParser
     *
     * @return none
     */
    private function getEmailFrom($html)
    {
        if ($this->_isForwarded) {
            //gets original sender email
            $tmpSenderEmail = $html->find('a');
            if (isset($tmpSenderEmail[0])) {
                $senderEmailLinks = new \SimpleXMLElement($tmpSenderEmail[0]);
                $this->emailModel->from['email'] = str_replace('mailto:', "", $senderEmailLinks['href']);
            }
            //gets original sender name
            $tmpSenderName = $html->find('.gmail_sendername');
            if (isset($tmpSenderName[0])) {
                $this->emailModel->from['name'] = (string)new \SimpleXMLElement($tmpSenderName[0]);
            }
        }
    }

}