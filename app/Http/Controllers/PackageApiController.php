<?php

namespace App\Http\Controllers;

use App\About;
use App\Blog;
use App\Category;
use App\City;
use App\Destination;
use App\Excursion;
use App\Option;
use App\Package;
use App\Slider;
use App\Social;
use App\Hotel;
use App\Policy;
use Illuminate\Http\Request;
use \ClassicO\NovaMediaLibrary\API;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PackageApiController extends Controller
{

/*Single Package*/

    public function gitSinglePackage($id)
    {
        $lang = app()->getLocale();
        // dd($lang)  // Get the current language
        
        $package = Package::where('id',$id)->with('destination')->orWhere("slug->$lang",$id)->firstOrFail();
        $x =DB::table('packages')->where('id',$package->id)->first();
        $y = json_decode($x->slug);
        $dest = Destination::WhereHas('packages', function ($q) use ($id,$lang) {
            $q->where('id', $id)
                ->orWhere("slug->$lang", $id);
        })->with('packages')
        ->firstOrFail();

        $val = $dest->packages->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });

        $data[] =  [
                   'destination'=>[
                       'id'=>$package->destination->id,
                       'slug'=>$package->destination->slug,
                       'name'=>$package->destination->name,
                   ],
                   'id'=>$val->id,
                   'name'=>$val->name,
                   'slug'=>[
                    'en' => $y->en,
                    'es' => $y->es,
                    'pt' => $y->pt,
                   ] ,
                   'description'=>$val->description,
                   'overview'=>$val->overview,
                   'discount'=>$val->discount,
                
                   'thumb_alt' => $val->thumb_alt,
                   'thumb' => asset('photos/' . $val->_thumb), 
                   'days' => $val->days,
                
                   'included' => $val->included_list ?? [],
                   'excluded' => $val->excluded_list ?? [],
               
                   'day_data' => $val->day_data_list ?? [],
                   'banner' => asset('photos/' . $val->_banner),

                   'banner_alt' => $val->alt,
                   'notes' => $val->notes,
                   'videos' => $val->videos_list ?? [],
                   'related_packages'=>$val->related_packages_list ?? [],
                   'gallery'=>$val->gallery_list ?? [],
                  
                   'location_package_map' =>$val->location_package_map ?? '',
               
                    'start_price' => $val->start_price,
                    'discount' => $val->discount,
                    'city' => City::whereIn('id', json_decode($val->city))->pluck('name')->toArray() ?? [],
                    'services' => Category::whereIn('id', json_decode($val->category, true))->pluck('name')->toArray() ?? [],
                    'Popular' => $val->hot_offer == 1,
                    'rate' => $val->rate,
                    'accessibility' => $val->accessibility == true,
                    'location' => $val->location_icon == true,
                    'tour_guide' => $val->tour_guide == true,
                    'schedule' => $val->schedule == true,
                    'transportation' => json_decode($val->transportation, true),
                    'languages' => json_decode($val->languages, true),
                    
                    'rate' => $val->rate,
                   'seo'=>[
                       'title' => $val->seo_title,
                       'keywords' => $val->seo_keywords,
                       'robots' => $val->seo_robots,
                       'description' => $val->seo_description,
                       'facebook_description' => $val->facebook_description,
                       'twitter_title' => $val->twitter_title,
                       'twitter_description' => $val->twitter_description,
                       'twitter_image' => $val->twitter_image,
                       'facebook_image' => $val->facebook_image,
                       'facebook_title'=>$val->og_title,
                       'twitter_image'=>asset('photos/' . $val->_twitter_image),
                       'facebook_image'=>asset('photos/' . $val->_facebook_image),
                   ]
                ];
              
        return response()->json([
            'data'=>$data
        ],'200');

    }


/*All packages Showed On Home Page  Apis */

    public function getAllPackages($lang)
    {
       // $data =  cache()->rememberForever('cache_package',  function () use ($lang){

        $query = Package::where([
           ['status','=',1],
           ['featured','=',1]
          ])->with('destination')->get();
        $packages = $query->map(function ($val){
            return [
                'destination'=>[
                    'slug'=>$val->destination->slug,
                ],
                'id'=>$val->id,
                'name'=>$val->name,
                'slug'=> $val->slug,
                'description'=>$val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'start_price' => $val->start_price,
                'days' => $val->days,
            ];

        });
        return response()->json([
            'data'=>[
                'packages'=>$packages,
            ]
        ]);
    }

    public function getMultiCountryPackages($lang)
    {
       // $data =  cache()->rememberForever('cache_package',  function () use ($lang){

        $query = Package::where([
           ['status','=',1],
           ['multi','=',1]
          ])->with('destination')->get();
        $packages = $query->map(function ($val){
            return [
                'destination'=>[
                    'slug'=>$val->destination->slug,
                ],
                'id'=>$val->id,
                'name'=>$val->name,
                'slug'=> $val->slug,
                'description'=>$val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'start_price' => $val->start_price,
                'days' => $val->days,
                'hot_offer' => $val->hot_offer,
            ];

        });
        return response()->json([
            'data'=>[
                'packages'=>$packages,
            ]
        ]);
    }

}
