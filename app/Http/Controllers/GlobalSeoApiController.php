<?php

namespace App\Http\Controllers;

use App\GlobalSeo;
use Illuminate\Http\Request;

class GlobalSeoApiController extends Controller
{
    public function getGlobaleSeo($lang){
        //$data =  cache()->rememberForever('cache_global_seo',  function () use ($lang){
            $globalSeo = GlobalSeo::get();
//        });


        return response()->json([
            'data'=>$globalSeo
        ]);
    }

}
