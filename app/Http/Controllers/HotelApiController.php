<?php

namespace App\Http\Controllers;

use App\City;
use App\Destination;
use App\Hotel;
use App\Option;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;
use function App\Nova\Flexible\Layouts\fields;

class HotelApiController extends Controller
{
    /*Single Hotel Api*/
    public function getSingleHotel($dest_id, $id)
    {
        $lang = app()->getLocale();  // Get the current language

        $hotel = Hotel::where('id', $id)->with('destination')->orWhere("slug->$lang", $id)->firstOrFail();
        $dest = Destination::where('id', $dest_id)
            ->orWhere('slug->en', $dest_id)
            ->WhereHas('hotels', function ($q) use ($id,$lang) {
                $q->where('id', $id)
                    ->orWhere("slug->$lang", $id);
            })->with('hotels')
            ->firstOrFail();

        $val = $dest->hotels->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });

        $gallery=array();
//            dd($item->gallery);
            if (is_array($val->_gallery)) {
             
                foreach ($val->_gallery as $key) {
                    if (array_key_exists('gallery',$key['attributes'])){
                        $image = asset('photos/'.$key['attributes']['gallery']);
                    }else{
                        $image = [];
                    }
                    if (array_key_exists('alt',$key['attributes'])){
                        $alt = $key['attributes']['alt'];
                    }else{
                        $alt = [];
                    }
                    $gallery[]=[
                        'alt'=>$alt,
                        'image'=>$image,
                    ];
                }
            }


        $data[] =  [
            'destination' => [
                'id' => $hotel->destination->id,
                'slug' => $hotel->destination->slug,
                'name' => $hotel->destination->name,
            ],
            'id' => $val->id,
            'name' => $val->name,
            'slug' => $val->slug,
            'description' => $val->description,
            'thumb_alt' => $val->thumb_alt,
            'thumb' => asset('photos/' . $val->_thumb),//API::getFiles($val->thumb),
            'rate' => $val->rate,
            'overview' => $val->overview,
            'country' => $val->country,
            'banner_alt' => $val->banner_alt,
            'checkin' => $val->checkin,
            'checkout' => $val->checkout,
            'services' => $val->service_list,
            'activities' => $val->activity_list,
            'amenities' => $val->amenity_list,
            'location_map' => $val->location,
            // 'logo' => API::getFiles($val->logo),
            'banner' =>asset('photos/' . $val->_banner), //API::getFiles($val->banner),
            'gallery' => $gallery,//API::getFiles($val->gallery, $imgSize = null, $object = true),
            'start_price' => $val->start_price ?? "",
            'free_barking' => $val->free_parking,
            'free_wifi' => $val->free_wifi,
            'air_condition' => $val->air_condition,
            'pool' => $val->pool,
            'gym' => $val->gym,
            'bathtub' => $val->bathtub,
            'bar' => $val->bar,
            'spa_and_wellness_centre' => $val->spa_and_wellness_centre,
            'family_rooms' => $val->family_rooms,
            'seo' => [
                'title' => $val->seo_title,
                'keywords' => $val->seo_keywords,
                'robots' => $val->seo_robots,
                'description' => $val->seo_description,
                'facebook_description' => $val->facebook_description,
                'twitter_title' => $val->twitter_title,
                'twitter_description' => $val->twitter_description,
                'facebook_title' => $val->og_title,
                'twitter_image' =>asset('photos/' . $val->_twitter_image), //API::getFiles($val->twitter_image),
                'facebook_image' =>asset('photos/' . $val->_facebook_image) //API::getFiles($val->facebook_image),
            ]
        ];

        return response()->json([
            'data' => $data
        ]);
    }

    public function getDestinationHotels($dest_id)
    {
        $standard_hotels = Hotel::with('destination')
            ->whereHas('destination', function ($query) use ($dest_id) {
                return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->where('hotel_category', 1)->where('status', 1)->get();

        $standard_hotels_data =  $standard_hotels->map(function ($val) {
            return [
                'destination' => [
                    'slug' => $val->destination->slug ?? []
                ],
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'rate' => $val->rate
            ];
        });

        $comfort_hotels = Hotel::with('destination')
            ->whereHas('destination', function ($query) use ($dest_id) {
                return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->where('hotel_category', 2)->where('status', 1)->get();

        $comfort_hotels_data =  $comfort_hotels->map(function ($val) {
            return [
                'destination' => [
                    'slug' => $val->destination->slug ?? []
                ],
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'rate' => $val->rate
            ];
        });

        $deluxe_hotels = Hotel::with('destination') 
            ->whereHas('destination', function ($query) use ($dest_id) {
                return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->where('hotel_category', 3)->where('status', 1)->get();

        $deluxe_hotels_data =  $deluxe_hotels->map(function ($val) {
            return [
                'destination' => [
                    'slug' => $val->destination->slug ?? []
                ],
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'rate' => $val->rate
            ];
        });
        $cruise_hotels = Hotel::with('destination')
            ->whereHas('destination', function ($query) use ($dest_id) {
                return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->where('hotel_category', 4)->where('status', 1)->get();

        $cruise_hotels_data =  $cruise_hotels->map(function ($val) {
            return [
                'destination' => [
                    'slug' => $val->destination->slug ?? []
                ],
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'rate' => $val->rate
            ];
        });
        // dd($data)  ;
        return response()->json([
            'data' => [
                'standard_hotels' => $standard_hotels_data ?? [],
                'comfort_hotels' => $comfort_hotels_data ?? [],
                'deluxe_hotels' => $deluxe_hotels_data ?? [],
                'cruise_hotels' => $cruise_hotels_data ?? [],
            ]
        ]);
    }

    public function getHotelsCities($country_id)
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
        $query->leftJoin('hotels', 'hotels.city_id', '=', 'cities.id');
        $query->leftJoin('destinations', 'hotels.destination_id', '=', 'destinations.id');
        if (is_numeric($country_id)) {
            $query->where('hotels.destination_id', $country_id);
        } else {
            $query->whereRaw("(destinations.slug like '%" . $country_id . "%')");
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

    public function getHotelList($dest_id, $city_id)
    {
        $standard_hotels = Hotel::with('destination','city')
            ->whereHas('destination', function ($query) use ($dest_id) {
                return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
            })->whereHas('city', function ($query) use ($city_id) {
                return $query->where('id', $city_id)->orWhere('slug->en', $city_id);
            })->where('status', 1)->paginate(15);

        $hotels_data =  $standard_hotels->map(function ($val) {
            return [
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'thumb_alt' => $val->thumb_alt,
                'thumb' => asset('photos/' . $val->_thumb),
                'rate' => $val->rate,
                'start_price' => $val->start_price ?? "",
                'free_barking' => $val->free_barking,
                'free_wifi' => $val->free_wifi,
                'air_condition' => $val->air_condition,
                'pool' => $val->pool,
                'gym' => $val->gym,
                'bathtub' => $val->bathtub,
                'bar' => $val->bar,
                'spa_and_wellness_centre' => $val->spa_and_wellness_centre,
                'family_rooms' => $val->family_rooms,
            ];
        });

        $destination=  $standard_hotels->map(function ($val) {
            return [
                
                    'name' => $val->destination->name ?? [],
                    'slug' => $val->destination->slug ?? []
              
            ];
        })->unique();

        $city =  $standard_hotels->map(function ($val) {
            return [
                
                
                    'name' => $val->city->name ?? [],
                    'slug' => $val->city->slug ?? [],
                    'description' => $val->city->hotel_description ?? [],
                    'banner' => API::getFiles($val->city->banner),
                    'alt' => $val->city->alt,
              
            ];
        })->unique();

        $seo =  $standard_hotels->map(function ($val) {
            return [
           
                    'title' => $val->city->hotel_seo_title,
                    'keywords' => $val->city->hotel_seo_keywords,
                    'robots' => $val->city->hotel_seo_robots,
                    'description' => $val->city->hotel_seo_description,
                    'facebook_description' => $val->city->hotel_facebook_description,
                    'twitter_title' => $val->city->hotel_twitter_title,
                    'twitter_description' => $val->city->hotel_twitter_description,
                    'facebook_title'=>$val->city->hotel_og_title,
                    'twitter_image'=>API::getFiles($val->city->hotel_twitter_image),
                    'facebook_image'=>API::getFiles($val->city->hotel_facebook_image),
            

            ];
        })->unique();

        // $comfort_hotels = Hotel::with('destination','city')
        //     ->whereHas('destination', function ($query) use ($dest_id) {
        //         return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
        //     })->whereHas('city', function ($query) use ($city_id) {
        //         return $query->where('id', $city_id)->orWhere('slug->en', $city_id);
        //     })->where('hotel_category', 2)->where('status', 1)->get();

        // $comfort_hotels_data =  $comfort_hotels->map(function ($val) {
        //     return [
        //         'destination' => [
        //             'slug' => $val->destination->slug ?? []
        //         ],
        //         'city' => [
        //             'slug' => $val->city->slug ?? []
        //         ],
        //         'id' => $val->id,
        //         'name' => $val->name,
        //         'slug' => $val->slug,
        //         'description' => $val->description,
        //         'thumb_alt' => $val->thumb_alt,
        //         'thumb' => API::getFiles($val->thumb),
        //         'rate' => $val->rate
        //     ];
        // });

        // $deluxe_hotels = Hotel::with('destination','city')
        //     ->whereHas('destination', function ($query) use ($dest_id) {
        //         return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
        //     })->whereHas('city', function ($query) use ($city_id) {
        //         return $query->where('id', $city_id)->orWhere('slug->en', $city_id);
        //     })->where('hotel_category', 3)->where('status', 1)->get();

        // $deluxe_hotels_data =  $deluxe_hotels->map(function ($val) {
        //     return [
        //         'destination' => [
        //             'slug' => $val->destination->slug ?? []
        //         ],
        //         'city' => [
        //             'slug' => $val->city->slug ?? []
        //         ],
        //         'id' => $val->id,
        //         'name' => $val->name,
        //         'slug' => $val->slug,
        //         'description' => $val->description,
        //         'thumb_alt' => $val->thumb_alt,
        //         'thumb' => API::getFiles($val->thumb),
        //         'rate' => $val->rate
        //     ];
        // });
        // $cruise_hotels = Hotel::with('destination','city')
        //     ->whereHas('destination', function ($query) use ($dest_id) {
        //         return $query->where('id', $dest_id)->orWhere('slug->en', $dest_id)->where('status', 1);
        //     })->whereHas('city', function ($query) use ($city_id) {
        //         return $query->where('id', $city_id)->orWhere('slug->en', $city_id);
        //     })->where('hotel_category', 4)->where('status', 1)->get();

        // $cruise_hotels_data =  $cruise_hotels->map(function ($val) {
        //     return [
        //         'destination' => [
        //             'slug' => $val->destination->slug ?? []
        //         ],
        //         'city' => [
        //             'slug' => $val->city->slug ?? []
        //         ],
        //         'id' => $val->id,
        //         'name' => $val->name,
        //         'slug' => $val->slug,
        //         'description' => $val->description,
        //         'thumb_alt' => $val->thumb_alt,
        //         'thumb' => API::getFiles($val->thumb),
        //         'rate' => $val->rate
        //     ];
        // });
       
        return response()->json([
            'data' => [
                'paginator' => [
                    'perPage' => $standard_hotels->perPage(),
                    'currentPage' => $standard_hotels->currentPage(),
                    'total' => $standard_hotels->total(),
                    'lastPage' => $standard_hotels->lastPage(),
                ],
                'destination' => $destination ?? [],
                'city' => $city ?? [],
                'seo' => $seo ?? [],
                'hotels_data' => $hotels_data ?? [],
                // 'comfort_hotels' => $comfort_hotels_data ?? [],
                // 'deluxe_hotels' => $deluxe_hotels_data ?? [],
                // 'cruise_hotels' => $cruise_hotels_data ?? [],
            ]
        ]);
    }

    // app/Http/Controllers/HotelController.php

    // app/Http/Controllers/HotelController.php

    public function filter(Request $request)
    {
        $query = Hotel::query();

        if ($request->has('start_price')) {
            $start_price = $request->input('start_price');
            if ($start_price === 'null') {
                $query->whereNull('start_price');
            } else {
                $query->where('start_price', '>=', $start_price);
            }
        }

        if ($request->has('rate')) {
            $rate = $request->input('rate');
            if ($rate === 'null') {
                $query->whereNull('rate');
            } else {
                $query->where('rate', '=', $rate);
            }
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }



}
