<?php

namespace App\Helpers;

use App\Channels\BaseChannel;

/**
 * Created by Gopal.
 *
 * @descriptions : that class contain most common function which we will use in entire project
 *
 */
class Curl
{

    const FAIL_CODE_BAD_REQUEST    = 1001;
    const FAIL_CODE_INTERNAL_ERROR = 1002;
    const FAIL_CODE_OTP            = 1100;
    const FAIL_CODE_FIREWALL       = 1101;

    const POST = 'POST';
    const GET  = 'GET';

    const RESPONSE_STATUS_SUCCESS = 'success';
    const RESPONSE_STATUS_ERROR = 'error';
    /**
     * call curl method
     *
     * @param $url    string mendatory
     * @param $params array optional
     * @param $method string (POST|GET|PUT|PATCH|DELETE) optional (default - POST)
     * @param $header array (array('Content-Type: application/json')) optional
     *
     * @return array
     */
    public static function call(
        string $url,
        array $params = [],
        string $method = self::POST,
        array $header = null,
        string $userpwd = ""
    ): array {

        if (is_null($header)) {
            $header = array('Content-Type: application/json');
        }

        $urlJson = json_encode($params);

        //Curl init 
        $curl = curl_init($url);

        //Curl Options
        $curlOptions = [
            CURLOPT_RETURNTRANSFER => 1,
            //CURLOPT_POST => 1,
            //CURLOPT_POSTFIELDS => $urlJson,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_TIMEOUT        => 30000
        ];

        //change curl options as per method
        switch ($method) {
            case self::POST:
                $curlOptions[CURLOPT_POSTFIELDS] = $urlJson;
                break;
            default:
                break;
        }
        if(!empty($userpwd)){
            $curlOptions[CURLOPT_USERPWD] = $userpwd;
        }

        curl_setopt_array($curl, $curlOptions);

        //execute curl and get response
        $resp = curl_exec($curl);
        //Check error in curl or not
        $success = ($resp !== false);
        if ($success) { // not exception on call - got valid response
            $resp = json_decode($resp, true);
            $success = (!empty($resp['success']) && ($resp['success']));
            $data = $resp['data'];
        } else {
            $data = curl_error($curl);
        }

        curl_close($curl);


        return SystemResponse::build($success, $data, $resp['context'] ?? null);
    }
}