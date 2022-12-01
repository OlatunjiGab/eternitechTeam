<?php

namespace App\Classes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Queue;

class CacheSet
{

    const CACHE_SET_PREFIX = "cache_set_prefix";
    const SEPARATOR        = "_";

    /* Redis Sorted Sets brief info:

    Redis Sorted Sets are similar to Redis Sets with the unique feature of values stored in a set. The difference is, every member of a Sorted Set is associated with a score, that is used in order to take the sorted set ordered, from the smallest to the greatest score.

    In Redis sorted set, add, remove, and test for the existence of members in O(1) (constant time regardless of the number of elements contained inside the set). 

    Maximum length of a list is 4294967295, more than 4 billion of elements per set.
    */

    /**
     * Push members to set. Here we are set 0 score for all elements.
     *
     * @param string $setName
     * @param array|string $items List of strings or single string
     */
    public static function push($setName, $items)
    {
        // adding score as timestamp to keep the FIFO order
        $score = Carbon::now()->timestamp;
        $scoresWithValues = [];
        if (is_array($items)) {
            foreach ($items as $item) {                
                $scoresWithValues[$item] = $score;
            }                                                                
        } else {            
            $scoresWithValues[$items] = $score;
        }

        self::pushScoredItems($setName, $scoresWithValues);
    }

    /**
     * Push single item to set. Here we are set 0 score for all elements.
     * Use when item is an object / array
     *
     * @param setname
     * @param Array of string or single string
     *
     * @return
     */
    protected static function pushScoredItems($setName, $scoreditems)
    {
        $key = self::getSetKeyBase($setName);        
        $count = Redis::zadd($key, $scoreditems);
    }

    /**
     * remove members from set
     *
     * @param setname
     * @param number of item
     *
     * @return array
     */
    public static function pop($setName, $count = PHP_INT_MAX)
    {
        $key = self::getSetKeyBase($setName);
        $setData = Redis::zPopMin($key, $count);

        return array_keys($setData);
    }

    /**
     * return  members
     *
     * @param setname
     *
     * @return array
     */
    public static function get($setName, $count = -1)
    {
        if ($count != -1) {
            $count--;
        }
        $key = self::getSetKeyBase($setName);
        $items = Redis::Zrange("$key", 0, $count);

        return $items;
    }

    /**
     * @param setname
     *
     * @return count
     */
    public static function count($setName)
    {
        $key = self::getSetKeyBase($setName);
        $count = Redis::zcard($key);

        return $count;
    }

    /**
     * clear set
     *
     * @param setname
     *
     * @return
     */
    public static function clearSet($setName)
    {
        $key = self::getSetKeyBase($setName);
        self::delete($key);
    }

    public static function delete($key)
    {
        Redis::del($key);
    }

    protected static function getSetKeyBase($setName)
    {
        return self::CACHE_SET_PREFIX . self::SEPARATOR . $setName;
    }

}
