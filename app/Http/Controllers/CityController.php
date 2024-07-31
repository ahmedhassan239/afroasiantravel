<?php

namespace App\Http\Controllers;

use App\City;

use ClassicO\NovaMediaLibrary\API;

class CityController extends Controller
{
    public function destinationCities($dest_id, $lang)
    {  
        $query = City::select(
            'cities.id',
            'cities.name as city_name',
            'cities.slug as city_slug',
            'cities._thumb as city_thumb',
            'cities.thumb_alt as city_thumb_alt',
            'destinations.name as destination_name',
            'destinations.slug as destination_slug',
        );
        $query->leftJoin('excursions', 'excursions.city_id', '=', 'cities.id');
        $query->leftJoin('destinations', 'excursions.destination_id', '=', 'destinations.id');
        if (is_numeric($dest_id)) {
            $query->where('excursions.destination_id', $dest_id);
        } else {
            $query->whereRaw("(destinations.slug like '%" . $dest_id . "%')");
        }
        $query->groupBy('id');
        $query = $query->get();
        $cities = $query->map(function ($value) {
            return [
                'destination'=>[
                    'name' => $value->destination_name,
                    'slug' => $value->destination_slug,
                ],
                'id' => $value->id,
                'name' => $value->city_name,
                'slug' => $value->city_slug,
                'thumb' =>asset('photos/' . $value->city_thumb),
                'thumb_alt' => $value->city_thumb_alt,
            ];
        });
        return response()->json([
            'data' => [
                'cities' => $cities,
            ]
        ], '200');
    }
}
