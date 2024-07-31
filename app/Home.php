<?php

namespace App;

use App\Traits\IsTranslatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Home extends Model
{
    public $table = "home";
    use IsTranslatable;
    use HasFlexible;
    use HasFactory;

    public $translatable = [
        'video_title_one', 'video_title_two', 'video_description', 'video',
        'category', 'packages', 'excursions', 'blogs', 'about_title', 'about_description',
        'tailor_title', 'tailor_description', 'why_title', 'why_description', 'features', 'partners'
    ];

    protected $casts = [
        'video_title_one' => 'array',
        'video_title_two' => 'array',
        'video_description' => 'array',
        'video' => 'array',
        'category' => 'array',
        'packages' => 'array',
        'excursions' => 'array',
        'blogs' => 'array',
        'about_title' => 'array',
        'about_description' => 'array',
        'tailor_title' => 'array',
        'tailor_description' => 'array',
        'why_title' => 'array',
        'why_description' => 'array',
        'features' => 'array',
        'partners' => 'array',
    ];

    public function getPackagesListAttribute()
    {
        if ($this->packages != Null) {
            return Package::whereIn('id', json_decode($this->packages))->with('destination')->get()
                ->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'days' => $value->days,
                        'start_price' => $value->start_price,
                        'Popular' => $value->hot_offer == 1,
                        'rate' => $value->rate,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                        'destination' => [
                            'id' => $value->destination->id,
                            'name' => $value->destination->name,
                            'slug' => $value->destination->slug,
                        ]
                    ];
                });
        }
    }

    public function getCategoryListAttribute()
    {
        if ($this->category != Null) {
            return Category::whereIn('id', json_decode($this->category))->get()
                ->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'short_description' => $value->short_description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
        }
    }
    public function getBlogsListAttribute()
    {
        if ($this->blogs != Null) {
            return Blog::whereIn('id', json_decode($this->blogs))->get()
                ->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                        'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->isoFormat('MMM Do YY'),

                        'destination' => [
                            'id' => $value->destination->id,
                            'name' => $value->destination->name,
                            'slug' => $value->destination->slug,
                        ]
                    ];
                });
        }
    }
    public function getExcursionsListAttribute()
    {
        if ($this->excursions != Null) {
            return Excursion::whereIn('id', json_decode($this->excursions))->get()
                ->map(function ($value) {
                    return [
                        'destination' => [
                            'id' => $value->destination->id,
                            'name' => $value->destination->name,
                            'slug' => $value->destination->slug,
                        ],
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'description' => $value->description,
                        'start_price' => $value->price_11,
                        'duration' => $value->duration,
                        'start_price' => $value->start_price,
                        'Popular' => $value->hot_offer == 1,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                });
        }
    }

  

    public function getFeaturesListAttribute()
    {
        $features = array();
        if (is_array($this->features)) {

            foreach ($this->features as $key) {
                $locale = $this->getLocale();

                // Set default values
                $title = $description = '';

                // Check if the 'title' and 'description' for the specific locale are set
                if (isset($key['attributes']['title'][$locale])) {
                    $title = $key['attributes']['title'][$locale];
                }
                if (isset($key['attributes']['description'][$locale])) {
                    $description = $key['attributes']['description'][$locale];
                }

                $features[] = [
                    'title' => $title,
                    'description' => $description,
                    'class' => $key['attributes']['class'],
                ];
            }
        }
        return $features;
    }


    public function getPartnersListAttribute()
    {
        $partners = array();
        if (is_array($this->partners)) {

            foreach ($this->partners as $key) {
                $partners[] = [
                    'alt' => $key['attributes']['alt'],
                    'link' => $key['attributes']['link'],
                    'image' => asset('photos/' . $key['attributes']['image']),
                ];
            }
        }
        return $partners;
    }
}
