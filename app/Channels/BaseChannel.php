<?php 
namespace App\Channels;

use App\Classes\Cache;
use App\Classes\Slack;
use App\Console\Commands\FetchLeadsCommand;
use App\Helpers\CommonUtils;
use App\Helpers\Curl;
use App\Helpers\Message;
use App\Helpers\SystemResponse;
use App\Jobs\FetchLeadsJob;
use App\Notifications\LogJob;
use Mail;

/**
* Auto-Bidding Base class 
*/

class BaseChannel
{
    const XPLACE = 'xplace';
    const FREELANCER = 'freelancer';
    const CRAIGSLIST = 'craigslist';
    const EMAIL = 'email';
    const NONE = 'none';
    const CRM = 'CRM';

    public $channel;

    public function __construct($channel) {
        $this->channel = $channel;
    }

    public function getCurrency() {
        throw new \Exception('channel ' . $this->channel . ' not implemented getCurrency function');
    }

    public function getDefaultMessageChannel()
    {
        throw new \Exception('channel ' . $this->channel . ' not implemented getDefaultMessageChannel function');
    }

    public function isBidAvailable() {
        throw new \Exception('channel ' . $this->channel . ' not implemented isBidAvailable function');
    }

    public static function getChannel($channel) {
        switch ($channel) {
            case self::FREELANCER:
                return new Freelancer($channel);
            case self::XPLACE:
                return new Xplace($channel);
            case self::CRAIGSLIST:
                return new Craigslist($channel);
            default:
                return null;
        }
    }


    /**
     * @return string
     */
	protected function getScraperDomain () {
	    return env('SCRAPER', '');
    }

    /**
     * place bid on channel
     *
     * @param $params array
     * @return array
     */
	public function placeBid(array $params):array{

		$url =  '/bid';
		$params['channel'] = $this->channel;

		return $this->scraperCall($url,$params);
	}

	/**
     * Send messge on channel
     *
     * @param $params array
     * @return bool
     */
	public function sendMessage(array $params) : bool{
		$url = '/message';

		$res =  $this->scraperCall($url,$params);
        return SystemResponse::isSuccess($res);
	}

    /**
     * fetch project details from channel
     *
     * @param $url
     * @param $channel
     *
     * @return array
     */
    public function fetchProjectDetails($url)
    {
        $payload = array('channel' => $this->channel, 'url' => $url);

        return $this->scraperCall('/project-details', $payload);
    }

    /**
     * get projects data
     *
     * @param $searchKeyword     string
     *
     * @return array
     */
    public function fetchNewProjects($searchKeyword = null)
    {
//        Slack::send(new LogJob(true, 'Starting fetch projects [' . $searchKeyword . ']', array('channel' => $this->channel)));

        $payload = array('channel' => $this->channel, 'keyword' => $searchKeyword ?? '');

        $res = $this->scraperCall('/projects', $payload);

        if (SystemResponse::isSuccess($res)) {
            $message = 'Found ' . count(SystemResponse::getData($res)) . ' projects  [' . $searchKeyword . ']';

            $projects = !empty(SystemResponse::getData($res)) ? array_column(SystemResponse::getData($res), 'url') : array();
            $projectsStr = implode($projects, '\r\n');;
        }

        return $res;
    }

    public static function getAllChannels() {
        return [
          self::XPLACE,
          self::CRAIGSLIST,
          self::EMAIL,
          self::CRM,
          self::NONE,
          self::FREELANCER,
        ];
    }

    public function dispatchNewProjectsJobs($isLocal = false) {
        $job = new FetchLeadsJob($this->channel);
        if ($isLocal) {
            $job->handle();
        } else {
            dispatch($job);
        }
    }

    public function getBotEventType () {
        return null;
    }

    /**
     * @param $response
     */
    public function handleRequestFail ($response, $url, $params) {
        $failCode = SystemResponse::getFailCode($response);
        switch ($failCode) {
            case Curl::FAIL_CODE_OTP:
                $key = strtolower(Curl::FAIL_CODE_OTP . $this->channel . $url);
                $payload = ['response' => $response, 'url' => $url, 'params' => $params, 'channel' => $this->channel];

                // add temporary job
                Cache::set($key, function () use ($payload){
                    return $payload;
                }, Cache::TIMEOUT_1HOUR);

                // send notification for OTP entry
                $config = array(
                    'template' => 'OTP_request',
                    'subject'  => 'Your One time Access Code is required',
                    'params'   => ['key' => $key, 'channel' => $this->channel],
                    'from'     => [LAConfigs::getByKey('default_email')],
                    'to'       => [Config::get('constant.adminEmailAddress')]
                );
                Message::sendSystemEmail($config);
            break;
        }
    }

    /**
     * @param string      $url
     * @param array       $params
     */
    public function scraperCall(
        string $url,
        array $params = []
    ): array {

        $response = Curl::call($this->getScraperDomain() . $url, $params);
        if (!SystemResponse::isSuccess($response)) {
            $this->handleRequestFail($response, $url, $params);

            Slack::send(new LogJob(false, 'Failed calling ' . $url, array('channel' => $this->channel, 'content' => print_r($response, true))));
        }
        return $response;
    }

    protected function getSearchKeyword($keyword) {
        return $keyword . " developer";
    }
}