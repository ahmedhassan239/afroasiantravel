<?php

namespace App;

use App\Observers\TravelGuideObserver;
use App\Traits\GlobalAttributes;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class TravelGuide extends Model
{
//    use HasTranslations;
      use IsTranslatable;
    use HasFlexible;
    public $table = "travel_guide";
    public $translatable = ['name','slug','description','overview','thumb_alt','alt','seo_title','seo_keywords',
        'seo_robots','seo_description','facebook_description','related_travel_guides',
        'twitter_title','twitter_description','related_packages_list','og_title'];


    protected $casts = [
        'gallery' => 'array',
    ];

//    protected $appends = ['related_pages_list','gallery_list','related_travel_guide_list','related_packages_list'];




    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

//    protected static function boot()
//    {
//        parent::boot();
//        static::observe(TravelGuideObserver::class);
//    }


    /* return related pages */
    public function getRelatedPagesListAttribute(){
        if ($this->related_pages != Null) {
            $related_pages = Page::whereIn('id', json_decode($this->related_pages,true))->with('category','destination')->get()
            // dd($related_pages);
                ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'category'=>[
                        'slug'=>$value->category->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description' => $value->description,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb),
                ];
            });
            return $related_pages;
        }
    }

    /* return gallery list  */
    // public function getGalleryListAttribute(){
    //     return API::getFiles($this->gallery);
    // }

    /* returun related travel guid */

    public function getRelatedTravelGuideListAttribute(){
        if ($this->related_travel_guides != Null) {
            $travel_guides = TravelGuide::whereIn('id',json_decode($this->related_travel_guides))
            ->with('destination')->get()->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description' => $value->description,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb), //API::getFiles($value->thumb)
                ];
            });
           return $travel_guides;
        }
    }
    public function getRelatedPackagesListAttribute(){
        if ($this->related_packages != Null) {
            $related_packages = Package::whereIn('id',json_decode($this->related_packages ))->with('destination')->get()
            ->map(function ($value) {
                return [
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'description'=>$value->description,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb),
                    'hot_offer'=> $value->hot_offer == 1
                ];
            });
            return $related_packages;
        }
    }

}
