<?php

namespace App\Http\Controllers;

use App\Guide;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class GuideApiController extends Controller
{
    public function getAllGuides($lang){

       // $data =  cache()->rememberForever('cache_guid',  function () use ($lang){

         $guides = Guide::where('status',1)->get(['id','name','slug','photo']);
        return response()->json([
            'data'=>$guides
        ],'200');
    }

    /*Single Guide Api*/
    public function getSingleGuides($id, $lang){
        $guide = Guide::where('status',1)->find($id);

        app()->setLocale($lang);

        $data= array();

            $id = $guide->id;
            $name =$guide->name;
            $slug = $guide->slug;
            $email = $guide->email;
            $description = $guide->description;
            $photo = API::getFiles($guide->photo, $imgSize = null, $object = false);
            $seo_title = $guide->seo_title;
            $seo_keywords = $guide->seo_keywords;
            $seo_robots = $guide->seo_robots;
            $seo_description = $guide->seo_description;
            $facebook_description = $guide->facebook_description;
            $twitter_title = $guide->twitter_title;
            $twitter_description = $guide->twitter_description;
            $og_title = $guide->og_title;
            $twitter_image = API::getFiles($guide->twitter_image);
            $facebook_image  = API::getFiles($guide->facebook_image);
            $data[]=[
                'id'=>$id,
                'name'=>$name,
                'slug'=>$slug,
                'photo'=>$photo,
                'email'=>$email,
                'description'=>$description,
                'seo'=>[
                    'title' => $seo_title,
                    'keywords' => $seo_keywords,
                    'robots' => $seo_robots,
                    'description' => $seo_description,
                    'facebook_description' => $facebook_description,
                    'facebook_title' => $og_title,
                    'twitter_title' => $twitter_title,
                    'twitter_description' => $twitter_description,
                    'twitter_image' => $twitter_image,
                    'facebook_image' => $facebook_image,
                ]
            ];

        return response()->json([
            'data'=>$data
        ],'200');
    }

}
