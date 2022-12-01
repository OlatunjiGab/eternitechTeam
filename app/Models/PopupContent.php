<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupContent extends Model
{
    protected $table = 'popup_contents';

    protected $fillable = ['title', 'content', 'background_image', 'specific_page', 'pop_after', 'country', 'source', 'status', 'company_id', 'created_at', 'updated_at'];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_LIST = [
        self::STATUS_ACTIVE => "Active",
        self::STATUS_INACTIVE => "Inactive",
    ];

    const SOURCE_DEFAULT = 1;
    const SOURCE_LIST = [
        self::SOURCE_DEFAULT => "Default",
    ];

    /**
     * Set the country
     *
     */
    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = json_encode($value);
    }

    /**
     * Get the country
     *
     */
    public function getCountryAttribute($value)
    {
        return $this->attributes['country'] = json_decode($value);
    }

    /**
     * Set the source
     *
     */
    public function setSourceAttribute($value)
    {
        $this->attributes['source'] = json_encode($value);
    }

    /**
     * Get the source
     *
     */
    public function getSourceAttribute($value)
    {
        return $this->attributes['source'] = json_decode($value);
    }
    /**
     * Set the status
     *
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = json_encode($value);
    }

    /**
     * Get the status
     *
     */
    public function getStatusAttribute($value)
    {
        return $this->attributes['status'] = json_decode($value);
    }

    public function upload(){
        return $this->hasOne(Upload::class,'id','background_image');
    }
}
