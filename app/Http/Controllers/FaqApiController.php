<?php

namespace App\Http\Controllers;

use App\Faq;
use Carbon\Carbon;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    /*Single Faq Api*/
    public function getSingleFaq($id, $lang)
    {
        $query = Faq::where('status','1');
        if(is_numeric($id)){
            $query->where('id',$id);
        }else{
            $query->whereRaw("(slug like '%".$id."%')");
        }
        $faq = $query->first();


        app()->setLocale($lang);

        $data= array();


        $id = $faq->id;
        $name = $faq->name;
        $slug = $faq->slug;
        $overview = $faq->overview;
        $description  = $faq->description;
        $thumb_alt = $faq->thumb_alt;
        $thumb = API::getFiles($faq->thumb, $imgSize = null, $object = false);
        $banner = API::getFiles($faq->banner, $imgSize = null, $object = false);
        $banner_alt = $faq->alt;
        $seo_title = $faq->seo_title;
        $seo_keywords = $faq->seo_keywords;
        $seo_robots = $faq->seo_robots;
        $seo_description = $faq->seo_description;
        $facebook_description = $faq->facebook_description;
        $twitter_title = $faq->twitter_title;
        $twitter_description = $faq->twitter_description;
        $og_title = $faq->og_title;
        $twitter_image =API::getFiles($faq->twitter_image) ;
        $facebook_image  =API::getFiles($faq->facebook_image);
        $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $faq->created_at)->isoFormat('MMM Do YY');

        $data[] = [
            'category' => [
                'slug' => 'myths-facts',
                'name' => 'Myths & Facts',
            ],
            'id'=>$id,
            'name'=>$name,
            'slug'=> $slug,
            'overview'=>$overview,
            'description'=>$description,
            'thumb_alt' => $thumb_alt,
            'banner_alt'=>$banner_alt,
            'thumb' => $thumb,
            'banner'=>$banner,
            'created_at' => $created_at,
           'seo'=>[
               'title'=>$seo_title,
               'keywords'=>$seo_keywords,
               'robots'=>$seo_robots,
               'description'=>$seo_description,
               'facebook_description'=>$facebook_description,
               'twitter_title'=>$twitter_title,
               'twitter_description'=>$twitter_description,
               'facebook_title'=>$og_title,
               'twitter_image'=>$twitter_image,
               'facebook_image'=>$facebook_image,
           ]
        ];
        return response()->json([
            'data'=>$data
        ]);
    }

}
