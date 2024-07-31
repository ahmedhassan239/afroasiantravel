<?php

namespace App\Http\Controllers;

use App\Category;
use App\Destination;
use App\Excursion;
use App\Option;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class ExcursionApiController extends Controller
{
    public function gitSingleExcursion($id)
    {
        $lang = app()->getLocale();  // Get the current language

        $dest = Destination::
            WhereHas('excursions', function ($q) use ($id,$lang) {
                $q->where('id', $id)
                    ->orWhere("slug->$lang", $id);
            })->with('excursions')
            ->firstOrFail();

        $val = $dest->excursions->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });

        $gallery=array();
//            dd($item->gallery);
            if (is_array($val->_gallery)) {
             
                foreach ($val->_gallery as $key) {
                    if (array_key_exists('gallery',$key['attributes'])){
                        $image = asset('photos/'.$key['attributes']['gallery']);
                    }else{
                        $image = [];
                    }
                    if (array_key_exists('alt',$key['attributes'])){
                        $alt = $key['attributes']['alt'];
                    }else{
                        $alt = [];
                    }
                    $gallery[]=[
                        'alt'=>$alt,
                        'image'=>$image,
                    ];
                }
            }

        $data[] = [
                'destination'=>[
                    'id'=>$val->destination->id,
                    'slug'=>$val->destination->slug,
                    'name'=>$val->destination->name,
                ],
                'category' => [
                    'slug' => 'travel-excursions',
                    'name' => 'Travel Excursions',
                ],
                'id'=>$val->id,
                'name'=>$val->name,
                'slug'=> $val->slug,
                'overview'=>$val->overview,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'arrive_location' => $val->arrive_location,
                'departure_location' => $val->departure_location,
                'location_description' => $val->location_description,
                'map_url' => $val->map_url,
                'duration' => $val->duration,
              
           
                'start_price' => $val->start_price,
                'price_after_discount' => $val->discount,
                'Popular' => $val->hot_offer == 1,
                'rate' => $val->rate,
                'accessibility' => $val->accessibility == true,
                'location' => $val->location_icon == true,
                'tour_guide' => $val->tour_guide == true,
                'schedule' => $val->schedule == true,
                'transportation' => json_decode($val->transportation, true),
                'languages' => json_decode($val->languages, true),
                'popular' => $val->hot_offer == 1,
                'gallery' =>$gallery ?? [] ,
                'included' => $val->include_list ?? [],
                'excluded' => $val->exclude_list ?? [],
                'banner' => asset('photos/' . $val->_banner),
                'banner_alt' => $val->alt,
                'gallery'=>$val->gallery_list ?? [],
                'seo'=>[
                    'title' => $val->seo_title,
                    'keywords' => $val->seo_keywords,
                    'robots' => $val->seo_robots,
                    'description' => $val->seo_description,
                    'facebook_description' => $val->facebook_description,
                    'twitter_title' => $val->twitter_title,
                    'twitter_description' => $val->twitter_description,
                    'facebook_title'=>$val->og_title,
                    'twitter_image'=>asset('photos/' . $val->_twitter_image),
                    'facebook_image'=>asset('photos/' . $val->_facebook_image),
                ],
                'related_excursions'=>$val->related_list,
                // 'reviews' =>$val->reviews_list,
                'city'=>[
                    'id'=>$val['city']['id'],
                    'name'=>$val['city']['name'],
                    'slug'=>$val['city']['slug'],
                ]

            ];

        return response()->json([
            'data'=>$data
        ]);
    }

}
