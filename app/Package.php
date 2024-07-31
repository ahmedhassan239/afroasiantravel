<?php

namespace App;


use App\Observers\PAckageObserver;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;


class Package extends Model
{
    public $table = "packages";
//    use HasTranslations;
     use IsTranslatable;
    use HasFlexible;
    public function getFlexibleContentAttribute()
    {
        return $this->flexible('flexible-content', [
            'wysiwyg' => \App\Nova\Flexible\Layouts\WysiwygLayout::class,
        ]);
    }


    public $translatable = ['name','slug','description','overview',
                            'thumb_alt','alt','seo_title','seo_keywords',
                            'seo_robots','seo_description','facebook_description',
                            'twitter_title','twitter_description',
                            'travel_experiences','day_data','optional_tours',
                            'related_packages_list','og_title','price_policy',
                            'payment_policy','repeated_travellers','travel_schedule','notes'
    ];
    /*we use this if we have some inputs saved as array*/
    protected $casts = [
        'videos' => 'array',
        'included'=>'array',
        'excluded'=>'array',
        // 'category'=>'array',
        'highlight'=>'array',
        'standard_hotels' => 'array',
        'comfort_hotels' => 'array',
        'deluxe_hotels' => 'array',
        'cruise_hotels' => 'array',
        'category_id' => 'array',
        'day_data' => 'array',
        'tour_type' => 'array',
        'reviews' => 'array',
        'date' => 'date',
        'optional_tours'=>'array',
        'gallery'=>'array',
        'related_packages_list'=>'array',
        // 'slug'=>'array',
        // 'city' => 'array',
        // 'category' => 'array',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
   
    
    public function categories()
    {
        return $this->belongsTo(Category::class,'category_id')->where('destination_id','=',$this->destination_id);
    }//END OF categories

    public function getStandardHotelListAttribute(){
        if ($this->standard_hotels != Null) {
           return Hotel::whereIn('id', json_decode($this->standard_hotels))->with('destination')->get()
            ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'rate' => $value->rate,
                    'thumb_alt' => $value->thumb_alt,
                    'rate'=>$value->rate,
                    'thumb' => asset('photos/' . $value->_thumb)//API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }
    }

    public function getComfortHotelListAttribute(){
        if ($this->comfort_hotels != Null) {
            return Hotel::whereIn('id', json_decode($this->comfort_hotels))->with('destination')->get()
             ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'rate' => $value->rate,
                    'thumb_alt' => $value->thumb_alt,
                    'rate'=>$value->rate,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });
        }

    }

    public function getDeluxeHotelListAttribute(){
        if ($this->deluxe_hotels != Null) {
            return Hotel::whereIn('id', json_decode($this->deluxe_hotels))->with('destination')->get()
            ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'rate' => $value->rate,
                    'thumb_alt' => $value->thumb_alt,
                    'rate'=>$value->rate,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });
        }

    }

    public function getCruiseHotelListAttribute(){
        if ($this->cruise_hotels != Null) {
              return  Hotel::whereIn('id', json_decode($this->cruise_hotels))->with('destination')->get()
              ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'rate' => $value->rate,
                    'thumb_alt' => $value->thumb_alt,
                    'rate'=>$value->rate,
                    'thumb' => asset('photos/' . $value->_thumb)
                    
                ];
            });
        }

    }

    public function getRelatedPackagesListAttribute(){
        if ($this->related_packages != Null) {
            return Package::whereIn('id',json_decode($this->related_packages))->with('destination')->get()
            ->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description' => $value->description,
                    'days'=>$value->days,
                    'start_price'=>$value->start_price,
                    'rate'=>$value->rate,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb),
                    'destination'=>[
                        'id' => $value->destination->id,
                        'name' => $value->destination->name,
                        'slug' => $value->destination->slug,
                    ]
                ];
            });
        }

    }

    public function getIncludedListAttribute(){
        if ($this->included != NULL) {
            return Option::whereIn('id',json_decode($this->included))->get('content');
        }

    }

    public function getExcludedListAttribute(){
        if ($this->excluded != NULL) {
            return Option::whereIn('id',json_decode($this->excluded))->get('content');
        }

    }

    public function getHighLightListAttribute(){
        if ($this->highlight != NULL) {
            return Option::whereIn('id',json_decode($this->highlight))->get('content');
        }

    }

    public function getDayDataListAttribute(){
        $day_data = array();
        if (is_array($this->day_data) ) {
            foreach ($this->day_data as $row) {
                if(array_key_exists(app()->getLocale(),$row['attributes']['day_title'])){
                    $day_summary[] = $row['attributes']['day_summary'][app()->getLocale()];
                }else{
                    $day_summary[] = '';
                }
                if(array_key_exists(app()->getLocale(),$row['attributes']['day_summary'])){
                    $day_title[] = $row['attributes']['day_title'][app()->getLocale()];
                }else{
                    $day_title[] = '';
                }
                $day_data[] = [
                    'day_number' => $row['attributes']['day_number'],
                    'day_title' => $day_title,
                    'day_summary' => $day_summary,
                    'breakfast' => $row['attributes']['breakfast'],
                    'lunch' => $row['attributes']['lunch'],
                    'dinner' => $row['attributes']['dinner'],
                ];
                $day_title = [];
                $day_summary = [];
            }

        }
        return $day_data;
    }

    public function getOptionalTourListAttribute(){
        $optional_tours = array();
        if (is_array($this->optional_tours) ) {
            foreach ($this->optional_tours as $row) {
                if(array_key_exists(app()->getLocale(),$row['attributes']['title'])){
                    $title = $row['attributes']['title'][app()->getLocale()];

                }else{
                    $title = [];
                }

                if(array_key_exists(app()->getLocale(),$row['attributes']['overview'])){
                    $overview = $row['attributes']['overview'][app()->getLocale()];
                }else{
                    $overview = [];
                }
                $optional_tours[] = [
                    'title' => $title,
                    'overview' => $overview,
                    'price' => $row['attributes']['price'],
                    'image' =>asset('photos/' . $row['attributes']['image']),
                ];
                $title = [];
                $overview = [];
            }

        }
        return $optional_tours;
    }

    public function getTravelExperiencesListAttribute(){
        $travel_experiences = array();
        if (is_array($this->travel_experiences)) {
            foreach ($this->travel_experiences as $row) {
                if(array_key_exists(app()->getLocale(),$row['attributes']['travel_experiences'])){
                    $travel_experiences[] = $row['attributes']['travel_experiences'][app()->getLocale()];
                }else{
                    $travel_experiences[] = '';
                }

            }
        }
        return $travel_experiences;
    }

    public function getReviewListAttribute(){
        $reviews = array();
        if (is_array($this->reviews) ) {
            foreach ($this->reviews as $row) {
                $reviews[] = [
                    'name' => $row['attributes']['name'],
                    'date' => $row['attributes']['date'],
                    'image' => API::getFiles( $row['attributes']['image'], $imgSize = null, $object = false),
                    //                    'country' => $row['attributes']['country'],
                    'rate' => $row['attributes']['rate'],
                    'review' => $row['attributes']['review'],
                ];
            }
        }
        return $reviews;
    }

    public function getVideosListAttribute(){
        $videos = array();
        if (is_array($this->videos)) {

            foreach ($this->videos as $row) {

                $videos[] = $row['attributes']['youtube_videos'];

            }
        }
        return $videos;
    }

public function getGalleryListAttribute(){
        $gallery = array();
        if (is_array($this->gallery)) {

                foreach ($this->gallery as $row) {
                    $gallery[] = [
                        'alt' => $row['attributes']['alt'],
                        'image' =>asset('photos/' . $row['attributes']['image']),
                       
                    ];
               
                }

        
        }
        return $gallery;
    }





//    public function scopeWithWhereHas($query, $relation, $constraint){
//        return $query->whereHas($relation, $constraint)
//            ->with([$relation => $constraint]);
//    }
}
