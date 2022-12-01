<?php

namespace App\Http\Controllers;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Curl;
use App\Helpers\SystemResponse;
use App\Models\Company;
use App\Models\Language;
use App\Models\ProjectCompany;
use App\Models\ProjectMessage;
use App\Models\TemplateMessage;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Classes\WriteLog;
use App\Channels\EmailParser\Parser;
use App\Channels\EmailParser\XplaceParser;
use \App\Models\Project;
use Sunra\PhpSimple\HtmlDomParser;

class IncomingController extends Controller
{

    public $_logFile = 'Inbound/log.txt';

    /**
     * handle all incoming mail data and parse html and save in DB
     *
     * @return \Illuminate\Http\Response
     */
    public function handleIncomingEmails(Request $request): string
    {
        try {
            $data = $request->all();
            $response = SystemResponse::build(false);

            if (isset($data) && !empty($data)) {
                if (array_key_exists('subject', $data) && !empty($data['subject'])) {
                    $this->logMessage("Start handle Incoming Emails IN IF POST " . "\r\n" . json_encode($data) . "End Incoming Emails");
                    $this->logMessage("email parse subject " . "\r\n" . print_r($data['subject'],
                            true) . " email parse subject ");

                    $this->logMessage(" To email id " . "\r\n" . print_r($data['to'], true) . " emailTo email id ");

                    $parser = new Parser($data);
                    $resData = $parser->parseData();

                    if (Parser::isSuccess($resData) && !empty(SystemResponse::getData($resData))) {
                        $parseData = array_filter(SystemResponse::getData($resData));

                        $this->logMessage("ParseData " . "\r\n" . json_encode($parseData) . " end");
                        if (!empty($parseData['type'])) {
                            switch ($parseData['type']) {
                                case Parser::DATA_TYPE_REPLY:
                                    // todo: implement a reply to message
                                    $project = Project::where('id', '=', $parseData['project_id'])->first();
                                    $project->sendMessage($parseData);
                                    break;
                                case Parser::DATA_TYPE_NEW_PROJECT:
                                default:
                                    $response = Project::createNewProject($parseData);
                                    break;
                            }
                        } else {
                            $this->logMessage("Start handle Incoming Emails POST " . "\r\n" . json_encode($request->all()) . "End Incoming Emails");
                        }
                    } else {
                        $this->logMessage("Error getting the email parse" . "\r\n" . json_encode($request->all()) . "End Incoming Emails");
                    }
                } else {
                    $this->logMessage("Event is not an incoming email " . "\r\n" . json_encode($request->all()));
                }
            } else {
                $this->logMessage("Unrecognized event " . "\r\n" . json_encode($request->all()));
            }
        } catch (\Exception $e) {
            $response = SystemResponse::build(false, $e->getMessage());
            $this->logMessage("Error " . $e->getMessage() . " " . json_encode($request->all()) . " End Incoming Emails");
            Slack::send(new ExceptionCought($e));
        }

        return json_encode($response);
    }

    public function logMessage($message)
    {
        WriteLog::write($this->_logFile, $message);
    }

}
