<?php

namespace App;

use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Excursion extends Model
{
    public $table = "excursions";
//    use HasTranslations;
      use IsTranslatable;

    /*we use this if we have some inputs saved as array*/
    protected $casts = [
        'gallery' => 'array',
        // '_gallery' => 'array',
        'included' => 'array',
        'excluded' => 'array',
        'reviews' => 'array',
        'date' => 'date',
    ];

//    protected $appends = ['include_list','exclude_list','related_list'];

    public $translatable = ['category_slug','name','slug','description',
        'overview','thumb_alt','alt','seo_title','seo_keywords','seo_robots',
        'seo_description','facebook_description','twitter_title','twitter_description','reviews','city_slug','city_name','destination_slug'
    ,'excursion_slug','destination_name','destination_description','excursion_description','destination_alt','excursion_thumb_alt',
        'destination_seo_title','destination_seo_keywords','destination_seo_robots','destination_seo_description','destination_facebook_description',
    'destination_twitter_title','map_url','destination_twitter_description','excursion_name','og_title','price_policy','payment_policy','repeated_travellers','travel_schedule'];


    public function destination(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

    public function categories ()

    {
        return $this->belongsTo(Category::class, 'category_id');
    }//END OF categories
    //
      public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }//END OF categories

    public function getIncludeListAttribute(){
        if ($this->included != NULL)
            return Option::whereIn('id',json_decode($this->included))->get('content');
        else   return '';
        }

    public function getExcludeListAttribute(){
        if ($this->excluded != NULL)
           return Option::whereIn('id',json_decode($this->excluded))->get('content');
        else return '';

    }

    public function getRelatedListAttribute(){
        if ($this->related_excursions != Null) {
            $related_excursions = Excursion::whereIn('id',json_decode($this->related_excursions))->with('destination')->get()
                ->map(function ($value) {
                return [
                    'destination'=>[
                        'name'=>$value->destination->name ?? [],
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description' => $value->description,
                    'rate' => $value->rate,
                    'duration' => $value->duration,
                    'start_price' => $value->price_11,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });
        }else{
            $related_excursions = '';
        }
        return $related_excursions;
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
//    public function getReviewsListAttribute(){
//        $reviews = array();
//        if (is_array($this->reviews) ) {
//            foreach ($this->reviews as $row) {
//                $reviews[] = [
//                    'name' => $row['attributes']['name'],
//                    'date' => $row['attributes']['date'],
//                    'image' => API::getFiles( $row['attributes']['image']),
//                    //                    'country' => $row['attributes']['country'],
//                    'rate' => $row['attributes']['rate'],
//                    'review' => $row['attributes']['review'],
//                ];
//            }
//        }
//        return $reviews;
//    }




}
