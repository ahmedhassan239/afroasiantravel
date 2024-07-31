<?php

namespace App\Http\Controllers;

use App\Testimonials;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class TestimonialApiController extends Controller
{
    public function getTestimonials()
    {

      //  $data =  cache()->rememberForever('cache_testimonial',  function (){
            $testimonials = Testimonials::get()->map(function ($testimonial){
               return [
                   'id'=>$testimonial->id,
                   'link'=>$testimonial->link,
                   'small_img_alt'=>$testimonial->small_img_alt,
                   'small_image_on_slider'=> asset('photos/' . $testimonial->small_img),
                   'large_img_alt'=>$testimonial->large_img_alt,
                   'large_image'=>asset('photos/' . $testimonial->large_img),
                   'showed_on_large_slider'=>$testimonial->showed_on_large_slider == 1,
               ];
            });
//            return $data;
//        });


        return response()->json([
            'data'=>$testimonials
        ],'200');
    }
}
