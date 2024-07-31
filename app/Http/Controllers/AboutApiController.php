<?php

namespace App\Http\Controllers;

use App\About;
use App\Blog;
use App\Category;
use App\Cruise;
use App\Excursion;
use App\Hotel;
use App\Package;
use App\Page;
use App\TravelGuide;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class AboutApiController extends Controller
{
    /*Abouts Apis*/
    public function getAbouts($lang)
    {
        app()->setLocale($lang);
        $data =  cache()->rememberForever('cache_about',  function () use ($lang) {

            $abouts = About::get()->map(function ($about){
                return  [
                    'id' => $about->id,
                    'first_title' => $about->first_title,
                    'first_block' => $about->first_block,
                    'second_title' => $about->second_title,
                    'second_block' => $about->second_block,
                    'title' => $about->title,
                    'description' => $about->description,
                    'video' =>asset('photos/' . $about->video),
                ];
            });
          return $abouts;
        });

        return response()->json([
            'data'=>$data
        ]);
    }

    public function zzz(){

        $names = TravelGuide::get();
        foreach($names as $row){
            $result = str_replace("alt=\"\"","alt=\"$row->name\"",$row->overview);

            $row->overview = $result;
            $row->save();

        }
    }

}
