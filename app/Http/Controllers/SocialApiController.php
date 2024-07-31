<?php

namespace App\Http\Controllers;

use App\Social;
use Illuminate\Http\Request;

class SocialApiController extends Controller
{
    /*Social Apis*/

    public function getSocials($lang)
    {

      //  $data =  cache()->rememberForever('cache_social',  function () use ($lang){
            $socials = Social::get();
        return response()->json([
            'data'=>$socials
        ]);
    }

}
