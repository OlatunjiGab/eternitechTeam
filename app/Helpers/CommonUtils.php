<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\HtmlString;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Mail;

/**
 * Created by Gopal.
 *
 * @descriptions : that class contain most common function which we will use in entire project
 *
 */
class CommonUtils
{
    /**
     * update env file detail as per environment
     *
     * @param $key   array|string mendatory
     * @param $value string optional
     * @param $delim string optional
     *
     * @return none
     */
    public static function updateDotEnv($key, $newValue = '', $delim = '')
    {

        $path = base_path('.env');

        if (is_array($key)) {
            foreach ($key as $k => $value) {
                $oldValue = env($k);

                if (file_exists($path)) {
                    file_put_contents($path, str_replace(
                        $k . '=' . $oldValue, $k . '=' . $value, file_get_contents($path)
                    ));
                }
            }
        } else {
            $oldValue = env($key);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    $key . '=' . $oldValue, $key . '=' . $newValue, file_get_contents($path)
                ));
            }
        }
    }
}