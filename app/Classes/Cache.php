<?php

namespace App\Classes;

use App\Jobs\CacheCalculationJob;
use Illuminate\Support\Facades\Redis;

class Cache
{
    const TIMEOUT_1MINUTE = 60;
    const TIMEOUT_1HOUR   = self::TIMEOUT_1MINUTE * 60;
    const TIMEOUT_6HOURS  = self::TIMEOUT_1HOUR * 6;
    const TIMEOUT_1DAY    = self::TIMEOUT_1HOUR * 24;
    const TIMEOUT_1WEEK   = self::TIMEOUT_1DAY * 7;
    const TIMEOUT_1MONTH  = self::TIMEOUT_1DAY * 30;
    const MIN_LIMIT       = 0;
    const MAX_LIMIT       = 1000;

    /**
     * @param $message
     * @param $clearLog
     */

    //Cache PREFIX
    const CACHE_PREFIX = "cache_";

    /**
     * @param           $key
     * @param null      $dataCallback
     * @param bool      $cacheBySession
     * @param float|int $timeout
     * @param bool      $defaultAsyncValue
     *
     * @return mixed
     * @throws \Exception
     */
    public static function get(
        $key,
        $dataCallback = null,
        $cacheBySession = false,
        $timeout = self::TIMEOUT_1DAY,
        $defaultAsyncValue = false
    ) {
        $key = self::CACHE_PREFIX . $key;

        if ($cacheBySession) {
            $key .= "_" . auth()->user()->id;
        }
        $data = cache($key);

        if (empty($data) && !empty($dataCallback)) {
           $data = self::set($key, $dataCallback, $cacheBySession, $timeout, $defaultAsyncValue);
        }

        return $data;
    }

    /**
     * @param           $key
     * @param           $dataCallback
     * @param bool      $cacheBySession
     * @param float|int $timeout
     * @param bool      $defaultAsyncValue
     *
     * @return mixed
     * @throws \Exception
     */
    public static function set(
        $key,
        $dataCallback,
        $cacheBySession = false,
        $timeout = self::TIMEOUT_1DAY,
        $defaultAsyncValue = false
    )
    {
        $key = self::CACHE_PREFIX . $key;

        if ($cacheBySession) {
            $key .= "_" . auth()->user()->id;
        }

        if (!isset($defaultAsyncValue) || empty($defaultAsyncValue)) {
            $data = $dataCallback();
            if (!is_numeric($timeout) && is_string($timeout)) {
                $timeout = $data[$timeout];
            }
            if (!is_numeric($timeout)) {
                throw new \Exception('Cache timeout is not numeric');
            }
            cache([$key => $data], $timeout); // 24 hours

            return $data;
        }
        if (!is_numeric($timeout)) {
            throw new \Exception('Cache timeout is not numeric');
        }
    }

    public static function forget($key_name)
    {
        $redis = \Cache::getRedis();
        $keys = $redis->keys("*$key_name*");
        $prefix = config('database.redis.options.prefix');
        foreach ($keys as $key) {
            $key = str_replace($prefix, '', $key);
            $redis->del($key);
        }
    }

    public static function getAllKeys()
    {
        $redisKey = [];
        $redis = \Cache::getRedis();
        $allkeys = $redis->keys("laravel:" . self::CACHE_PREFIX . "*");
        if (!empty($allkeys)) {
            foreach ($allkeys as $key) {
                $singleKey = explode(':', $key);
                array_push($redisKey, $singleKey[1]);
            }
        }

        return $redisKey;
    }

    public static function has($key,$cacheBySession =false)
    {
        $key = self::CACHE_PREFIX . $key;

        if ($cacheBySession) {
            $key .= "_" . auth()->user()->id;
        }

        return \Cache::has($key);
    }

    public static function increment($key, $count = null)
    {
        $key = self::CACHE_PREFIX . $key;
        if ($count) {
            return \Cache::increment($key, $count);
        } else {
            return \Cache::increment($key);
        }
    }

    // use to check if the cache is already in queue or not
    public static function getQueueList($queueName)
    {
        $thejobs = array();
        $numJobs = Redis::connection()->llen($queueName);
        $jobs = Redis::connection()->lrange($queueName, self::MIN_LIMIT, self::MAX_LIMIT);
        foreach ($jobs as $job) {
            // Each job here is in json format so decode to object form
            $tmpdata = json_decode($job);
            if (isset($tmpdata->data) && !empty($tmpdata->data)) {
                $data = $tmpdata->data;
                $job = unserialize($data->command);
                $thejobs[] = $job;
            }
        }

        return $thejobs;
    }
}
