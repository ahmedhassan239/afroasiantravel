<?php

namespace App;

use App\Observers\PageObserver;
use App\Traits\GlobalAttributes;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    public $table = "pages";
//    use HasTranslations;
    use IsTranslatable;
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::observe(PageObserver::class);
//    }


    public $translatable = ['destination_slug','category_thumb_alt','category_slug','category_name',
                            'name','page_title','slug','description','thumb_alt','category_alt','category_description',
                            'alt','category_seo_title','category_seo_keywords','category_seo_robots','category_seo_description','category_seo_title',
                            'category_facebook_description','category_twitter_title','category_twitter_description','destination_name',
                            'seo_title','seo_keywords','seo_robots','seo_description','seo_title',
                            'facebook_description','related_pages_list','twitter_title','twitter_description'
                            ,'accordion','accordion_list','og_title','related_travel_guides','related_excursions_list',
                            'related_cruises_list','related_blogs_list','related_packages_list'
                            ];

    protected $casts = [
        'gallery' => 'array',
        '_gallery' => 'array',
        'accordion' => 'array',
        'related_pages_list'=> 'array',
        'related_packages_list'=>'array',
        'related_blogs_list'=> 'array',
        'related_cruises_list'=> 'array',
        'related_excursions_list'=> 'array',
    ];

//    protected $appends = ['accordion_list','related_pages_list','gallery_list'];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->where('showed','!=',1)
            ->where('status','=',1);
    }//END OF categories


    /* return accordion list */
    public function getAccordionListAttribute()
    {
        $page_accordion = array();
        if (is_array($this->accordion)) {
            $y=1;
            foreach ($this->accordion as $key) {
                $page_accordion[]=[
                    'id'=>$y,
                    'key'=>$key['key'],
                    'category_title'=>$key['attributes']['title'][$this->getLocale()],
                    'category_description'=>$key['attributes']['description'][$this->getLocale()],
                ];
                $y++;
            }
        }
        return $page_accordion;

    } // End of getAccordionAttribute

    /* return related pages */
    public function getRelatedPagesListAttribute(){
        if ($this->related_pages != Null) {
            $related_pages = $this->whereIn('id', json_decode($this->related_pages,true))->with('category','destination')->get()
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
    public function getRelatedPackagesListAttribute(){
        if ($this->related_packages != Null) {
            return Package::whereIn('id',json_decode($this->related_packages))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? []
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'days'=>$value->days,
                        'start_price'=>$value->start_price,
                        'rate'=>$value->rate,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
        }

    }

    public function getRelatedBlogsListAttribute(){
        if ($this->related_blogs  != Null) {
            $related_blogs  = Blog::whereIn('id', json_decode($this->related_blogs ,true))->with('destination')->get()
                ->map(function ($value) {
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
            return $related_blogs;
        }
    }
    public function getRelatedCruisesListAttribute(){
        if ($this->related_cruises  != Null) {
            $related_cruises  = Cruise::whereIn('id', json_decode($this->related_cruises  ,true))->with('destination')->get()
                ->map(function ($value) {
                    return [
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
            return $related_cruises;
        }
    }

    public function getRelatedExcursionsListAttribute(){
        if ($this->related_excursions  != Null) {
            $related_excursions  = Excursion::whereIn('id', json_decode($this->related_excursions ,true))->with('destination','city')->get()
                ->map(function ($value) {
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
                        'description' => $value->description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb)
                    ];
                });
            return $related_excursions;
        }
    }

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
                    'thumb' => asset('photos/' . $value->_thumb),
                ];
            });
           return $travel_guides;
        }
    }
    /* return gallery list  */
    public function getGalleryListAttribute(){
         return API::getFiles($this->gallery);
    }

}
