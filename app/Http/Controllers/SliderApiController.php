<?php

namespace App\Http\Controllers;
use App\Slider;

use \ClassicO\NovaMediaLibrary\API;



class SliderApiController extends Controller
{
    /*All Slider Apis*/
    public function getAllSliders($lang)
    {
        $sliders = Slider::where('status',1)->get()
            ->map(function ($val){
                return [
                    'id'=>$val->id,
                    'slider_data' => $val->slider_data_list ?? [],
                    'alt'=> $val->alt,
                    'ImageOrVideo'=>asset('photos/' . $val->video_image)
                ];
            });

        return response()->json([
            'data'=>$sliders
        ]);

    }

    public function sunmarine(){
        
            return response()->json([
                'sun'=> 0
            ]);
           
        }
   

}
