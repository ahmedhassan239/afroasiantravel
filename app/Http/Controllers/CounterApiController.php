<?php

namespace App\Http\Controllers;

use App\Counter;
use Illuminate\Http\Request;

class CounterApiController extends Controller
{
    public function getCounter(){
        $counters = Counter::get();
        $data = array();
        foreach ($counters as $counter){
            $id = $counter->id;
            $tour_listed = $counter->tour_listed;
            $verified_agent = $counter->verified_agent;
            $satisfied_customer = $counter->satisfied_customer;
            $data = [
                'id'=>$id,
                'tour_listed'=>$tour_listed,
                'verified_agent'=>$verified_agent,
                'satisfied_customer'=>$satisfied_customer
            ];
        }

        return response()->json([
            'data'=>$data
        ]);


    }
}
