<?php

namespace App\Http\Controllers;

use App\Category;
use App\Home;
use Illuminate\Http\Request;

class HomeApiController extends Controller
{
    public function index()
    {
        $query = Home::first();
        $data =  [
            'video'=>[
                'video_title_one'=>$query->video_title_one,
                'video_title_two'=>$query->video_title_two,
                'video_description'=>$query->video_description,
                'video'=> asset('photos/'.$query->video)
            ],
            'services'=>$query->category_list ?? [],
            'packages'=>$query->packages_list ?? [],
            'blogs'=>$query->blogs_list ?? [],
            'excursions'=>$query->excursions_list ?? [],
            'aboutus'=>[
                'about_title'=>$query->about_title,
                'about_description'=>$query->about_description,
            ],
            'tailor_made'=>[
                'tailor_title'=>$query->tailor_title ,
                'tailor_description'=>$query->tailor_description,
            ],
            'why_choose_us'=>[
                'why_title'=>$query->why_title,
                'why_description'=>$query->why_description,
            ],
            'features'=>$query->features_list ?? [],
        ];
        return response()->json([
            'data'=>$data
        ],'200');
        
        }

        public function partners (){
            $query = Home::first();
            $data =  ['partners'=>$query->partners_list ?? []];
            return response()->json([
                'data'=>$data
            ],'200');
        }
    

}
