<?php

namespace App\Http\Controllers;

use App\City;
use App\Option;
use App\Excursion;
use Illuminate\Http\Request;
use ClassicO\NovaMediaLibrary\API;

class CityExcursionController extends Controller
{
    public function excursionCities($city_id,$lang)
    {
//        $data =  cache()->rememberForever('cache_city_ex_'.$city_id,  function () use ($city_id,$lang) {

            $query = Excursion::where('status',1)->
                whereHas(
                    'city',function($x)use ($city_id){
                        $x ->where('id', $city_id)->orWhere('slug->en', $city_id);
                    }
                )->with('destination')->get();

// dd($query);
            $excursions = $query->map(function ($val){
                return [
                    'id' => $val->id,
                    'slug' => $val->slug,
                    'name' => $val->name,
                    'description' => $val->description,
                    'thumb_alt' => $val->thumb_alt,
                    'thumb' => asset('photos/' . $val->_thumb),
                    'start_price' => $val->price_11,
                    'location_map' => $val->location_url,
                    'duration' => $val->duration,
                    'travellers' => $val->travellers,
                    'rate' => $val->rate,
                    'meals' => $val->meals,
                    'services_number' => $val->services_no,
                    'activities_number' => $val->activities_no,
                    'guide_tour' => $val->guide_tour,
                    'discount' => $val->discount,
                    'best_sale' => $val->top_sale == 1 ,
                    'hot_offer' => $val->hot_offer == 1,
                ];
            });
        $city= $query->map(function ($val){
            return [
                'id' => $val->city->id,
                'name' => $val->city->name,
                'slug' => $val->city->slug,
                'description' => $val->city->description,
                'alt' => $val->city->alt,
                'banner' =>asset('photos/' . $val->city->_banner),
                
                ];
            })->unique();
        $destination= $query->map(function ($val){
            return [
                'id' => $val->destination->id,
                'slug' => $val->destination->slug,
                'name' => $val->destination->name,
                'description' => $val->destination->description,
             
                ];
            })->unique();
        $seo= $query->map(function ($val){
            return [
                'title' => $val->city->seo_title,
                'keywords' => $val->city->seo_keywords,
                'robots' => $val->city->seo_robots,
                'description' => $val->city->seo_description,
                'facebook_description' => $val->city->facebook_description,
                'twitter_title' => $val->city->twitter_title,
                'twitter_description' => $val->city->twitter_description,
                'facebook_title'=>$val->city->og_title,
                'twitter_image'=>asset('photos/' . $val->city->_twitter_image),
                'facebook_image'=>asset('photos/' . $val->city->_facebook_image),
                ];
            })->unique();

            return response()->json([
                'data'=>[
                    'destination'=>$destination,
                    'city'=>$city,
                    'excursions'=>$excursions,
                    'seo'=>$seo,
                ],
            ]);
    }


}
