<?php

namespace App\Channels\EmailParser;

use App\Channels\BaseChannel;
use App\Helpers\Curl;
use App\Helpers\SystemResponse;
use App\Models\Language;
use App\Models\Project;
use Sunra\PhpSimple\HtmlDomParser;
use App\Classes\WriteLog;

/**
 * Created by Gopal.
 *
 * Descriptions : by this adapter class ,we can parse mail those comes from xplace channel
 *
 */
class XplaceParser extends BaseParser
{

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
     * @param $data
     *
     * @return $response array
     */
    public function parseData($data)
    {

        $xplaceData = [];
        $dom = new \DomDocument();
        $dom->strictErrorChecking = false;
        $dom->loadHTML(($data['html']));
        $projectUrl = '';
        foreach ($dom->getElementsByTagName('a') as $item) {
            foreach ($this->_xplaceProjectUrl as $uri) {
                if (stripos($item->getAttribute('href'), $uri) !== false) {
                    $this->emailModel->projectURL = $item->getAttribute('href');
                    $projectUrl = $item->getAttribute('href');
                    break;
                }
            }
        }

        $channel = BaseChannel::getChannel(BaseChannel::XPLACE);
        if (!$channel) {
            throw new \Exception('channel ' . BaseChannel::XPLACE . '  could\'t be found');
        }
        $resData = $channel->fetchProjectDetails($projectUrl);

        if (!empty($this->emailModel->projectURL)) {
            if (SystemResponse::isSuccess($resData) && !empty(SystemResponse::getData($resData))) {
                $xplaceData = array_filter(SystemResponse::getData($resData));
            }

            if (!empty($xplaceData) && isset($xplaceData['title']) && !empty($xplaceData['title'])) {
                $response = array(
                    'channel'    => self::PARSER,
                    'type'       => Parser::DATA_TYPE_NEW_PROJECT,
                    'email'      => $this->emailModel->from['email'],
                    'name'       => $this->emailModel->from['client_name'] ?? '',
                    'phone'      => $this->emailModel->phone,
                    'subject'    => ($xplaceData['title']),
                    'text'       => ($xplaceData['description']),
                    'attachment' => $this->_aAttachment,
                    'skill'      => $xplaceData['categories'],
                    'categories' => $xplaceData['categories'],
                    'url'        => $this->emailModel->projectURL,
                    'language'   => Language::detectLanguage($xplaceData['title']),
                    'client'     => $xplaceData['client_name']
                );
            }
        }
        if (isset($response)) {
            WriteLog::write("Inbound/log.txt",
                " XplaceParser response data " . "\r\n" . print_r($response, true) . " XplaceParser response data ");

            return SystemResponse::build(true, $response);
        } else {
            return SystemResponse::build(false, 'project not found');
        }
    }
}