<?php
namespace App\Helpers;

use Mail;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Models\Project;
use App\Models\TemplateMessage;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Dwij\Laraadmin\Models\LAConfigs;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Nexmo\Laravel\Facade\Nexmo;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

use App\Helpers\CustomHelper;
use App\Classes\WriteLog;
use SendGrid\Mail\From;
use SendGrid\Mail\Mail as SendgridMail;
use SendGrid\Mail\OpenTracking;
use SendGrid\Mail\TrackingSettings;

/**
 * Created by Gopal.
 *
 * @descriptions : that class contain most common function which we will use in entire project 
 * 
 */

class Message
{
    const CHANNEL_SMS     = 'sms';
    const CHANNEL_EMAIL   = 'email';
    const CHANNEL_PHONE   = 'phone';
    const CHANNEL_SCRAPER = 'channel';

    const CHANNELS = [
      self::CHANNEL_SMS,
      self::CHANNEL_EMAIL,
      self::CHANNEL_PHONE,
      self::CHANNEL_SCRAPER,
    ];
    //const SMS_FROM_NUMBER = '+17867249241';

    public static function send($messageChannel, Project $project, array $message)
    {
        switch ($messageChannel) {
            case self::CHANNEL_SMS:
                return self::sendSMS($project, $message);
            case self::CHANNEL_EMAIL:
                return self::sendEmail($project, $message);
            case self::CHANNEL_SCRAPER:
                return self::sendScraper($project, $message);
            case self::CHANNEL_PHONE:
            default:
                return self::sendScraper($project, $message);
        }
    }

    public static function validateSend($messageChannel, Project $project)
    {
        switch ($messageChannel) {
            case self::CHANNEL_SMS:
            case self::CHANNEL_PHONE:
                $company = $project->company();
                return !empty($company->phone);
            case self::CHANNEL_EMAIL:
                $company = $project->company();
                return !empty($company->email);
            case self::CHANNEL_SCRAPER:
                $channelObj = BaseChannel::getChannel($project->channel);
                return !empty($channelObj);
            default:
                return false;
        }
    }

    public static function sendEmail($project, $message)
    {
        if (empty($project->company()->email)) {
            return false;
        }

        $config = array(
            'template' => 'send_message',
            'subject' => $message['subject'] ?? null,
            'params' =>  ["msg"=>$message['message'] ?? null],
            'to_email' => str_replace("\n","",$project->company()->email),
            'to_name' => str_replace("\n","",$project->company()->name)
        );

        $senderDetails = $project->getActiveSender($message['isAutomated'] ?? false);

        $defaults = array_merge(array('sendAs'=>'html','template'=>'default','body'=>'No message','title'=>'Simple','subject'=>'Subject'),$config);

        $body =  view('emails.'.$defaults['template'], $defaults['params']);
        $body =  new HtmlString(with(new CssToInlineStyles)->convert($body));

        // TODO: check email bounce before sending

        $mail = new SendgridMail();
        $tracking_settings = new TrackingSettings();
        $tracking_settings->setOpenTracking(
            new OpenTracking(true, "--sub--")
        );
        $mail->setTrackingSettings($tracking_settings);
        $sender = new From($senderDetails['email'], $senderDetails['name']);
        $mail->setFrom($sender);
        $mail->setSubject($defaults['subject']);
        $mail->addTo($defaults['to_email'], $defaults['to_name']);
        //$mail->addContent("text/plain", $text);
        $mail->addContent("text/html", "<div>" . $body . "</div>");
        $sg = new \SendGrid(env('SENDGRID_API_KEY'));
        $sgMessageId = "";
        try {
            $response = $sg->send($mail);
            $context = json_decode($response->body());
            if ($response->statusCode() == 202) {
                $sgHeaders = $response->headers(true);
                $sgMessageId = $sgHeaders['X-Message-Id'];
            } else {
                Slack::send(new LogJob(true, 'Email sent failed (' . $response->statusCode() . ')',  array('channel' => BaseChannel::EMAIL, 'content' => print_r($context, true)), LogJob::CHANNEL_EMAILS));
                Log::error("Failed to send email", ["context" => $context]);
            }
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
            Log::error($e);
        }

        return $sgMessageId;
    }

    public static function sendSMS($project, $message){

        return true; // until new SMS provider will be implement
        $text = $message['message'];
        $company = $project->company();

        if (empty($text)) {
            throw new Exception('Cannot send empty message');
        }

        if (strlen($text) > 140 ) {
            throw new Exception('Cannot send message over 140 characters in SMS');
        }

        $res = Nexmo::message()->send([
            'to'   => $company->phone,
            'from' => env('SMS_FROM_NUMBER','+17867249241'),
            'text' => $text
        ]);

        return true;
    }

    public static function sendScraper($project, array $message){
        if (empty($message['message'])) {
            throw new Exception('Cannot send message to channel without message');
        }

        $channelObj = BaseChannel::getChannel($project->channel);
        if (!$channelObj) {
            throw new \Exception('channel ' . $project->channel . ' couldn\'t be found');
        }
        return $channelObj->sendMessage([
            'channel'   => $project->channel,
            'url' => $project->url,
            'message' => $message // array with subject and message
        ]);
    }

    public static function toString (array $message, $strMessage = ''){
        //$strMessage = '';
        foreach ($message as $label => $value) {
            $strMessage .= '<b>' . $label . ':</b> ' . print_r($value, true) . TemplateMessage::LINE_SEPARATOR;
        }
        return $strMessage;
    }

    public static function getMessageChannelOfScrapingChannel($channel) {
        // if scraper is the prefferec method - get the scraper channel message channel (as not all support scrape communication)
        $scrapingChannel = BaseChannel::getChannel($channel);
        if (!$scrapingChannel) {
            throw new \Exception('channel ' . $channel . ' could\'t be found');
        }
        return $scrapingChannel->getDefaultMessageChannel();

    }

    public static function sendSystemEmail($config)
    {
        // as it is not requiring tracking (customers tracking) - we are sending using default mail
        $defaults = array_merge(array('sendAs'=>'html','template'=>'default','body'=>'No message','title'=>'Simple','subject'=>'Subject'),$config);

        if ($defaults['template']) {
            $body = view('emails.' . $defaults['template'], $defaults['params']);
        } else {
            $body = $defaults['params']['msg'];
        }
        $body =  new HtmlString(with(new CssToInlineStyles)->convert($body));

        // add the basic header footer
        $res = Mail::send('emails.default', ['title' => $defaults['title'], 'body' => $body], function ($message) use($defaults){
            if(isset($defaults['from'])){
                $message->from($defaults['from']);
            } else {
                $message->from(LAConfigs::getByKey('default_email'), 'Eternitech Team');
            }
            $message->to($defaults['to']);
            $message->subject($defaults['subject']);
            if(isset($defaults['attachments']) && count($defaults['attachments']) > 0) {
                foreach ($defaults['attachments'] as $attachment) {
                    $message->attach($attachment);
                }
            }
        });

        return true;
    }


}