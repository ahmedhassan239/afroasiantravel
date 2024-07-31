<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Hotel extends Model
{
    public $table = "hotels";
    use HasTranslations;
    /*we use this if we have some inputs saved as array*/
    protected $casts = [
        'gallery' => 'array',
        '_gallery' => 'array',
        'amenities' => 'array',
        'activities' => 'array',
        'services' => 'array',
    ];



    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    } //END OF destination

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    } //END OF destinationy

    public $translatable = [
        'hotel_name', 'name', 'slug', 'description',
        'overview', 'thumb_alt', 'alt', 'seo_title', 'seo_keywords', 'seo_robots',
        'seo_description', 'facebook_description', 'twitter_title', 'twitter_description', 'og_title'
    ];


    public function getserviceListAttribute()
    {
        if ($this->services != null) {
            $query = Option::whereIn('id', json_decode($this->services))->get('content');
            // $query  = $query->groupBy('category');
            return $query;
        } else {
            return [];
        }
    }
    public function getActivityListAttribute()
    {
        if ($this->activities != null) {
            $query = Option::whereIn('id', json_decode($this->activities))->get('content');
            // $query  = $query->groupBy('category');
            return $query;
        } else {
            return [];
        }
    }

    public function getAmenityListAttribute()
    {
        if ($this->amenities != null) {
            $query = Option::select('content', 'category')->whereIn('id', json_decode($this->amenities))->get();
            $query  = $query->groupBy('category');
            return $query;
        } else {
            return [];
        }
    }
}
