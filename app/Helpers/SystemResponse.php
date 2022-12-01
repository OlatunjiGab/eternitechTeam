<?php

namespace App\Helpers;

/**
 * Created by Gopal.
 *
 * @descriptions : that class contain most common function which we will use in entire project
 *
 */
class SystemResponse
{
    const ATTRIBUTE_SUCCESS = 'success';
    const ATTRIBUTE_DATA = 'data';
    const ATTRIBUTE_CONTEXT = 'context';

    const ATTRIBUTE_FAIL_DETAILS = 'fail_details';
    const ATTRIBUTE_FAIL_CODE = 'code';
    const ATTRIBUTE_FAIL_MESSAGE = 'message';

    public static function build($success, $data = null, $context = null)
    {
        $response = [];
        $response[self::ATTRIBUTE_SUCCESS] = $success;
        if ($context) {
            $response[self::ATTRIBUTE_CONTEXT] = $context;
        }

        if ($success && is_array($data)) {
            $response[self::ATTRIBUTE_DATA] = $data;
        } else {
            $response[self::ATTRIBUTE_DATA] = json_decode($data, true);
        }

        return $response;
    }

    public static function isSuccess($response) {
        return $response[self::ATTRIBUTE_SUCCESS];
    }

    public static function getContext($response) {
        return $response[self::ATTRIBUTE_CONTEXT];
    }

    public static function getData($response) {
        return $response[self::ATTRIBUTE_DATA];
    }

    public static function getFailMessage($response) {
        return $response[self::ATTRIBUTE_FAIL_DETAILS][self::ATTRIBUTE_FAIL_MESSAGE] ?? null;
    }

    public static function getFailCode($response) {
        return $response[self::ATTRIBUTE_FAIL_DETAILS][self::ATTRIBUTE_FAIL_CODE] ?? null;
    }
    
    public static function getDataAttribute($response, $value) {
        $data = self::getData($response);
        return $data[$value] ?? null;
    }
}