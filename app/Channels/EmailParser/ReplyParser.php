<?php
namespace App\Channels\EmailParser;

use App\Classes\Slack;
use App\Helpers\Curl;
use App\Classes\WriteLog;
use App\Helpers\SystemResponse;
use App\Notifications\ExceptionCought;
use \EmailReplyParser\Parser\EmailParser;

/**
 * Created by Gopal.
 *
 * Descriptions : by this adapter class ,we can parse mail those comes from xplace channel
 * 
 */

class ReplyParser extends BaseParser {

    const PARSER = Parser::XPLACE;

    public static function detectParser($data)
    {
        $subject = isset($data['subject']) && !empty($data['subject']) ? explode(" ",
            strtolower($data['subject'])) : [];

        return in_array(self::PARSER, $subject);
    }

    /**
     * parseData : incoming email data parse 
     * 
     * @param $data mandrillObject
     * @return $response array
     */
    public function parseData($data)
    {
        try {
            $response = SystemResponse::build(false);

            if (isset($data) && !empty($data)) {
                preg_match("/<body[^>]*>(.*?)<\/body>/is", $data['html'], $matches);
                $htmlData = strip_tags($matches[1]);
                //$htmlData = str_replace('\n', ' ', trim($htmlData));
                $htmlData = trim(preg_replace("/\r|\t|\n/", "", $htmlData));
                $htmlData .= "Here is another email Sent from my iPhone";
                $email = (new EmailParser())->parse($htmlData);//$emailContent

                $fragment = current($email->getFragments());

                $response = [
                    'type' => Parser::DATA_TYPE_REPLY,
                    'project_id'   => 58,
                    'message'      => trim($fragment->getContent()),
                    'message_html' => $htmlData,
                ];
            }

            WriteLog::write("Inbound/log.txt", "ReplyParser response data " . "\r\n" . print_r($response,
                    true) . " ReplyParser response data ");

            return SystemResponse::build(true, $response);
        } catch (\Exception $e) {
            $response = SystemResponse::build(false, $e->getMessage());
            WriteLog::write($this->_logFile,
                "Error " . $e->getMessage() . " " . print_r($data,true) . " End Incoming Emails");

            Slack::send(new ExceptionCought($e));
        }
    }
}