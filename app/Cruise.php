<?php

namespace App;

use App\Observers\CruiseObserver;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Cruise extends Model
{
    public $table = "cruises";
    use HasTranslations;
    use HasFlexible;

/*we use this if we have some inputs saved as array*/
    protected $casts = [
        'gallery' => 'array',
        '_gallery' => 'array',
        'services' => 'array',
        'activities' => 'array',
        'reviews' => 'array',
        'date' => 'date',
        'prices' => 'array',
    ];
    public $translatable = ['name','slug','description','overview','thumb_alt',
        'alt','seo_title','seo_keywords','seo_robots','seo_description',
        'facebook_description','twitter_title','twitter_description',
        'travel_experiences','day_data','four_nights','seven_nights','category_slug','category_name','og_title'
        ,'price_policy','payment_policy','repeated_travellers','travel_schedule'];

    protected static function boot()
    {
        parent::boot();

        static::observe(CruiseObserver::class);
    }


    public function categories ()
    {
        return $this->belongsTo(Category::class,'category_id');
    }//END OF categories
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
}
