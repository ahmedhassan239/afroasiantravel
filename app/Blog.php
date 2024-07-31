<?php

namespace App;

use App\Observers\BlogObserver;
use App\Traits\GlobalAttributes;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $table = "blogs";
//    use HasTranslations;

    use IsTranslatable;
    public $translatable = ['name','slug','overview','description','thumb_alt','alt','banner_alt','seo_title',
        'seo_keywords','seo_robots','seo_description','facebook_description','twitter_title',
        'twitter_description','travel_experiences','related_pages_list','related_package_list','related_blogs_list',
        'related_cruises_list','related_excursions_list','og_title'];

    protected $hidden = ['status','featured'];
    protected $casts =[
        'created_at'=>'datetime:M Y',
        'related_pages_list'=> 'array',
        'related_packages_list'=>'array',
        'related_blogs_list'=> 'array',
        'related_cruises_list'=> 'array',
        'related_excursions_list'=> 'array',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

    public function categories ()
    {
     return $this->belongsTo(Category::class, 'category_id');
     
    }//END OF categories

    /* return related pages */
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
    public function getRelatedPackagesListAttribute(){
        if ($this->related_packages != Null) {
            return Package::whereIn('id',json_decode($this->related_packages))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'destination'=>[
                            'slug'=>$value->destination->slug ?? [],
                            'name'=>$value->destination->name ?? []
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
            $related_blogs  = $this->whereIn('id', json_decode($this->related_blogs ,true))->with('destination')->get()
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
                        // 'thumb' =>asset('photos/' . $value->_thumb), //API::getFiles($value->thumb)
                        'thumb' => asset('photos/' . $value->_thumb),//API::getFiles($value->thumb)
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
            $related_excursions  = Excursion::whereIn('id', json_decode($this->related_excursions ,true))->with('destination')->get()
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
                        'thumb' => asset('photos/' . $value->_thumb)
                    ];
                });
            return $related_excursions;
        }
    }

}
