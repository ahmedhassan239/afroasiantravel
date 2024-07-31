<?php

namespace App;

use App\Observers\CityObserver;
use App\Traits\GlobalAttributes;
use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Model;
//use App\City;

class City extends Model
{
    //
    use IsTranslatable;
    public $table = "cities";

    public $translatable = ['slug','name','description','thumb_alt','alt','city_slug','city_name','destination_name',
        'destination_slug','excursion_slug','excursion_name','destination_description','excursion_description',
        'destination_alt','excursion_thumb_alt','destination_seo_title','destination_seo_keywords','destination_seo_robots','destination_seo_description',
        'destination_facebook_description','destination_twitter_title','destination_twitter_description',
        'seo_title','seo_keywords',
        'seo_robots','seo_description','facebook_description',
        'twitter_title','twitter_description','og_title','city_thumb_alt',
        'hotel_seo_title','hotel_seo_keywords','hotel_seo_robots','hotel_seo_description',
        'hotel_og_title','hotel_facebook_description','hotel_twitter_title','hotel_twitter_description',
        'hotel_facebook_image','hotel_twitter_image',
        'hotel_description'];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }


    //END OF destination
    public function excursions()
    {
        return $this->hasMany(Excursion::class, 'city_id');
    }//End OF Excursion
    protected static function boot()
    {
        parent::boot();

        static::observe(CityObserver::class);
    }




}
