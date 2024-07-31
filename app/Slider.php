<?php

namespace App;

use App\Observers\SliderObserver;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    public $table = "sliders";
//    use HasTranslations;
      use IsTranslatable;
    public $translatable = ['alt','slider_data_list','slider_data'];

    protected $casts = [
        'slider_data' => 'array',
        'image' => 'array'
    ];


    protected static function boot()
    {
        parent::boot();

        static::observe(SliderObserver::class);
    }
    public function getSliderDataListAttribute()
    {
//        dd($this->slider_data);
        $slider_data = array();
        if (is_array($this->slider_data)) {
            $y=1;
            foreach ($this->slider_data as $key) {
                $slider_data[]=[
                    'id'=>$y,
                    'title'=>$key['attributes']['title'][$this->getLocale()],
                    'small_text'=>$key['attributes']['small_text'][$this->getLocale()],
                    'call_to_action_title'=>$key['attributes']['call_to_action_title'][$this->getLocale()],
                    'call_to_action_link'=>$key['attributes']['call_to_action_link'][$this->getLocale()],
                    'image'=>API::getFiles($key['attributes']['image'], $imgSize = null, $object = false),
//                    'image'=>$key->image,
                ];
                $y++;
            }
        }
        return $slider_data;

    } // End of


//    public function getImageAttribute($value){
//        return API::getFiles($value);
//    }
}
