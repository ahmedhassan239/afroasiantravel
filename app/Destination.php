<?php

namespace App;

use App\Nova\Metrics\Hotels;
use App\Observers\DestinationObserver;

use App\Traits\GlobalAttributes;

use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;


class Destination extends Model
{
    public $table = "destinations";

    use IsTranslatable;



    protected static function boot()
    {
        parent::boot();

        static::observe(DestinationObserver::class);
    }


    public $translatable = ['destination_name','destination_slug',
                            'package_slug','package_name',
                            'destination_description','package_description',
                            'destination_alt','package_thumb_alt',
                            'category_name','name','slug','description',
                            'thumb_alt','alt','seo_title','seo_keywords',
                            'seo_robots','seo_description','facebook_description',
                            'twitter_title','twitter_description','excursion_slug',
                             'excursion_name','excursion_description','excursion_thumb_alt',
                            'destination_seo_title','destination_seo_keywords','destination_seo_robots',
                            'destination_seo_robots','destination_seo_description','destination_facebook_description',
                            'destination_twitter_title','destination_twitter_description','content','destination_banner_alt',
                            'cruise_slug','cruise_name','cruise_thumb_alt','cruise_description','travel_guide_slug','travel_guide_name',
                            'travel_guide_description','travel_guide_thumb_alt','overview',
                            'destination_slug','name','slug','city_name','city_slug','og_title'];

    public function category()
    {
        return $this->hasMany(Category::class, 'destination_id')->where('status', '=', 1)->where('showed','!=',1);

    }//End OF Category
    public function categories()
    {
        return $this->hasMany(Category::class, 'destination_id');

    }//End OF Category


    public function city()
    {
        return $this->hasMany(City::class, 'destination_id');
    }//End OF City

    public function category_faq()
    {
        return $this->hasMany(Category::class, 'destination_id')->where('status', '=', 1)->where('faq','=',1);

    }//End OF Category

    public function packages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Package::class, 'destination_id')->where('status', '=', 1);
    }//End OF packages

//   public function cruses()
//    {
//        return $this->hasMany(Cruise::class, 'destination_id')->where('status', '=', 1);
//    }//End OF cruses

    public function excursions()
    {
        return $this->hasMany(Excursion::class, 'destination_id')->where('status', '=', 1);
    }//End OF excursion
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'destination_id')->where('status', '=', 1);
    }//End OF excursion

    public function pages()
    {
        return $this->hasMany(Page::class, 'destination_id')->where('status','=', 1);
    }//End OF excursion

    public function sidePhotos()
    {
        return $this->hasMany(SidePhoto::class, 'destination_id');
    }//End OF excursion
    public function travelGuides()
    {
        return $this->hasMany(TravelGuide::class, 'destination_id')->where('status', '=', 1);
    }//End OF excursion

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'destination_id')->where('status', '=', 1);
    }//End OF excursion

    public function cruises()
    {
        return $this->hasMany(Cruise::class, 'destination_id')->where('status', '=', 1);
    }//End OF excursion



    public function getRelatedPagesListAttribute(){
        if ($this->related_pages != Null) {
            $related_pages = Page::whereIn('id', json_decode($this->related_pages,true))->with('category','destination')->get()
            // dd($related_pages);
                ->map(function ($value) {
                return [
                    'category'=>[
                        'slug'=>$value->category->slug ?? []
                    ],
                    'destination'=>[
                        'slug'=>$value->destination->slug ?? []
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

    public function getPackagesHotOffersListAttribute(){
        if ($this->packages_hot_offers != Null) {
           return  Package::whereIn('id', json_decode($this->packages_hot_offers))
           ->with('destination')->get()->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb)
                    ];
                });
        }
    }

    public function getExcursionsHotOffersListAttribute(){
        if ($this->excursions_hot_offers != Null) {
            return Excursion::whereIn('id', json_decode($this->excursions_hot_offers))->with('destination','city')
                ->get()->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'city'=>[
                            'slug'=>$value->city->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb)
                    ];
                });
        }
    }

    public function getCruisesHotOffersListAttribute(){
        if ($this->cruises_hot_offers != Null) {
            return Cruise::whereIn('id', json_decode($this->cruises_hot_offers))->with('destination')
                ->get()->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
        }
    }

}
