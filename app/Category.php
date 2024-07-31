<?php

namespace App;

use App\Observers\CategoryObserver;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    public $table = "categories";
//    use HasTranslations;
        use IsTranslatable;


    protected $casts = [
        'accordion' => 'array',
        'related_pages_list'=> 'array'
    ];

//    protected $appends = ['accordion_list'];
    protected static function boot()
    {
        parent::boot();

        static::observe(CategoryObserver::class);
    }
    protected $hidden = ['deleted_at','status','showed','faq'];

    public $translatable = ['name','slug',
                            'description','thumb_alt','alt', 'seo_title','seo_keywords','seo_robots',
                            'seo_description','facebook_description','twitter_title','twitter_description',
                            'twitter_image','facebook_image','accordion','destination_slug','accordion_list',
                            'title','og_title','related_pages_list','short_description'];
                     

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
    public function getAccordionListAttribute($value)
    {
        $category_accordion = array();
//            dd($page->category_accordion);
        if (is_array($this->accordion)) {
            $y=1;
            foreach ($this->accordion as $key) {
                $category_accordion[]=[
                    'id'=>$y,
                    'key'=>$key['key'],
                    'category_title'=>$key['attributes']['title'][$this->getLocale()],
                    'category_description'=>$key['attributes']['description'][$this->getLocale()],
                ];
                $y++;
            }
        }

        return $category_accordion;
    } // End of getAccordionAttribute


    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination

    public function packages()
    {
        return $this->hasMany(Package::class, 'destination_id')->where('status', '=', 1);
    }//End OF packages

    public function excursions()
    {
        return $this->hasMany(Excursion::class, 'category_id')->where('status', '=', 1);
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'category_id')->with('category')->where('status', '=', 1);
    }//End OF excursions








}
