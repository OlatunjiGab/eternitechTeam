<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColumnSetting extends Model
{
    protected $table = 'column_settings';
    public $timestamps = true;

    public static function getColumnSettingList() {
        $list = self::select('key','value')->pluck('value','key')->toArray();
        return $list;
    }
}
