<?php

namespace App\Http\Controllers;


use App\Category;
use App\Cruise;
use App\Destination;
use App\Hotel;
use App\Option;
use App\Policy;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class CruiseApiController extends Controller
{
    /*All Cruises Api*/
    public function getAllCruises($lang)
    {
        //        $data =  cache()->rememberForever('cache_cruise',  function () use ($lang) {
        $cruises = Cruise::select('cruises.*', 'categories.slug as category_slug', 'categories.name as category_name')
            ->leftJoin('categories', 'category_id', '=', 'categories.id')
            ->where('cruises.status', 1)->get();

        $data = array();

        foreach ($cruises as $cruise) {

            $id = $cruise->id;
            $name = $cruise->name;
            $slug = $cruise->slug;
            $description = $cruise->description;
            $thumb_alt = $cruise->thumb_alt;
            $rate = $cruise->rate;
            $category_slug = $cruise->category_slug;
            $category_name = $cruise->category_name;
            $thumb = asset('photos/' . $cruise->_thumb);

            $data[] = [
                'id' => $id,
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'thumb_alt' => $thumb_alt,
                'thumb' => $thumb,
                'rate' => $rate,
                'category' => [
                    'slug' => $category_slug,
                    'name' => $category_name,
                ]

            ];
        }
        //            return $data;
        //        });
        return response()->json([
            'data' => $data
        ]);
    }

    private function fetchHotels($cruise) {
        if (!$cruise->hotels) return [];
        
        $hotels = Hotel::whereIn('id', json_decode($cruise->hotels))->get();
        return $hotels->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'rate' => $value->rate,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => asset('photos/' . $value->_thumb)
            ];
        });
    }
    
    private function fetchReviews($cruise) {
        if (!is_array($cruise->reviews)) return [];
        
        $reviews = [];
        foreach ($cruise->reviews as $row) {
            $reviews[] = [
                'name' => $row['attributes']['name'],
                'date' => $row['attributes']['date'],
                'image' => API::getFiles($row['attributes']['image'], $imgSize = null, $object = false),
                'rate' => $row['attributes']['rate'],
                'review' => $row['attributes']['review'],
            ];
        }
        return $reviews;
    }
    
    public function fetchRelatedCruises($relatedCruisesIds)
    {
        if ($relatedCruisesIds != null) {
            $relatedCruises = Cruise::whereIn('id', json_decode($relatedCruisesIds))
                ->with('destination')
                ->get()
                ->map(function ($cruise) {
                    return [
                        'destination' => [
                            'slug' => $cruise->destination->slug ?? []
                        ],
                        'id' => $cruise->id,
                        'name' => $cruise->name,
                        'slug' => $cruise->slug,
                        'description' => $cruise->description,
                        'rate' => $cruise->rate,
                        'days' => $cruise->days,
                        'start_price' => $cruise->double_room_price,
                        'thumb_alt' => $cruise->thumb_alt,
                        'thumb' => asset('photos/' . $cruise->_thumb),
                    ];
                });
        } else {
            $relatedCruises = '';
        }
    
        return $relatedCruises;
    }
    
    
    /*Single Cruise Api*/
    public function getSingleCruise($dest_id, $id, $lang)
    {
        $dest = Destination::where('id', $dest_id)
            ->orWhere('slug->en', $dest_id)
            ->WhereHas('cruises', function ($q) use ($id) {
                $q->where('id', $id)
                    ->orWhere('slug->en', $id);
            })->with('cruises')
            ->firstOrFail();
        $policy = Policy::where('destination_id',$dest->id)->first();

        $cruise = $dest->cruises->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });
        app()->setLocale($lang);

        $data = array();


        $id = $cruise->id;
        $name = $cruise->name;
        $slug = $cruise->slug;
        $description = $cruise->description;
        $overview = $cruise->overview;
        $thumb_alt = $cruise->thumb_alt;
        $banner_alt = $cruise->alt;
        $checkin = $cruise->checkin;
        $checkout = $cruise->checkout;

        if ($cruise->services != NULL) {
            $services = Option::whereIn('id', json_decode($cruise->services))->get('content');
        } else {
            $services = '';
        }


        if ($cruise->activities != NULL) {
            $activities = Option::whereIn('id', json_decode($cruise->activities))->get('content');
        } else {
            $activities = '';
        }


        $double_room_price = $cruise->double_room_price;
        $single_room_price = $cruise->single_room_price;
        $rate = $cruise->rate;
   


        $thumb =  asset('photos/' . $cruise->_thumb);
        $banner =  asset('photos/' . $cruise->_banner);
        $gallery = array();
        //            dd($item->gallery);
        if (is_array($cruise->_gallery)) {

            foreach ($cruise->_gallery as $key) {
                if (array_key_exists('gallery', $key['attributes'])) {
                    $image = asset('photos/' . $key['attributes']['gallery']);
                } else {
                    $image = [];
                }
                if (array_key_exists('alt', $key['attributes'])) {
                    $alt = $key['attributes']['alt'];
                } else {
                    $alt = [];
                }
                $gallery[] = [
                    'alt' => $alt,
                    'image' => $image,
                ];
            }
        }
        $seo_title = $cruise->seo_title;
        $seo_keywords = $cruise->seo_keywords;
        $seo_robots = $cruise->seo_robots;
        $seo_description = $cruise->seo_description;
        $facebook_description = $cruise->facebook_description;
        $twitter_title = $cruise->twitter_title;
        $twitter_description = $cruise->twitter_description;

        $facebook_title = $cruise->og_title;
        $twitter_image = asset('photos/' . $cruise->_twitter_image);
        $facebook_image = asset('photos/' . $cruise->_facebook_image);

        $price_policy = $cruise->price_policy;
        $payment_policy = $cruise->payment_policy;
        $repeated_travellers = $cruise->repeated_travellers;
        $travel_schedule = $cruise->travel_schedule;

        $every_day_3 = $cruise->every_day_3;
        $three_nights = array();
        if (is_array($cruise->day_data)) {

            foreach ($cruise->day_data as $row) {
                if (array_key_exists($lang, $row['attributes']['day_summary'])) {
                    $day_summary = $row['attributes']['day_summary'][$lang];
                } else {
                    $day_summary = '';
                }

                if (array_key_exists($lang, $row['attributes']['day_title'])) {
                    $day_title = $row['attributes']['day_title'][$lang];
                } else {
                    $day_title = '';
                }


                $three_nights[] = [
                    'day_number' => $row['attributes']['day_number'],
                    'day_title' => $day_title,
                    'day_summary' => $day_summary,
                    'breakfast' => $row['attributes']['breakfast'],
                    'lunch' => $row['attributes']['lunch'],
                    'dinner' => $row['attributes']['dinner'],
                ];
                $day_title = '';
                $day_summary = '';
            }
        }
        $every_day_4 = $cruise->every_day_4;
        $four_nights = array();
        if (is_array($cruise->four_nights)) {

            foreach ($cruise->four_nights as $row) {
                if (array_key_exists($lang, $row['attributes']['day_summary'])) {
                    $day_summary = $row['attributes']['day_summary'][$lang];
                } else {
                    $day_summary = '';
                }

                if (array_key_exists($lang, $row['attributes']['day_title'])) {
                    $day_title = $row['attributes']['day_title'][$lang];
                } else {
                    $day_title = '';
                }


                $four_nights[] = [
                    'day_number' => $row['attributes']['day_number'],
                    'day_title' => $day_title,
                    'day_summary' => $day_summary,
                    'breakfast' => $row['attributes']['breakfast'],
                    'lunch' => $row['attributes']['lunch'],
                    'dinner' => $row['attributes']['dinner'],
                ];
                $day_title = '';
                $day_summary = '';
            }
        }
        $every_day_7 = $cruise->every_day_7;
        $seven_nights = array();
        if (is_array($cruise->seven_nights)) {

            foreach ($cruise->seven_nights as $row) {
                if (array_key_exists($lang, $row['attributes']['day_summary'])) {
                    $day_summary = $row['attributes']['day_summary'][$lang];
                } else {
                    $day_summary = '';
                }

                if (array_key_exists($lang, $row['attributes']['day_title'])) {
                    $day_title = $row['attributes']['day_title'][$lang];
                } else {
                    $day_title = '';
                }


                $seven_nights[] = [
                    'day_number' => $row['attributes']['day_number'],
                    'day_title' => $day_title,
                    'day_summary' => $day_summary,
                    'breakfast' => $row['attributes']['breakfast'],
                    'lunch' => $row['attributes']['lunch'],
                    'dinner' => $row['attributes']['dinner'],
                ];
                $day_title = '';
                $day_summary = '';
            }
        }

        $prices = array();
        if (is_array($cruise->prices)) {

            foreach ($cruise->prices as $row) {


                $prices[] = [
                    'duration' => $row['attributes']['duration'],
                    'season' => $row['attributes']['season'],
                    'start_from' => $row['attributes']['start_from'],
                    'triple_cabin' => $row['attributes']['triple_cabin'],
                    'double_cabin' => $row['attributes']['double_cabin'],
                    'single_cabin' => $row['attributes']['single_cabin'],
                ];
            }
        }

        $travel_experiences = array();
        if (is_array($cruise->travel_experiences)) {

            foreach ($cruise->travel_experiences as $row) {
                if (array_key_exists($lang, $row['attributes']['travel_experiences'])) {
                    $travel_experiences[] = $row['attributes']['travel_experiences'][$lang];
                } else {
                    $travel_experiences[] = '';
                }
            }
        }


        $data[] = [
            'destination' => [
                'id' => $cruise->destination->id,
                'slug' => $cruise->destination->slug,
                'name' => $cruise->destination->name,
            ],
            'category' => [
                'slug' => 'travel-cruises',
                'name' => 'Travel Cruises',
            ],
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'thumb_alt' => $thumb_alt,
            'thumb' => $thumb,
            'rate' => $rate,
            'overview' => $overview,
            'banner_alt' => $banner_alt,
            'every_day_3' => $every_day_3,
            'three_nights' => $three_nights,
            'every_day_4' => $every_day_4,
            'four_nights' => $four_nights,
            'every_day_7' => $every_day_7,
            'seven_nights' => $seven_nights,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'hotels' => $this->fetchHotels($cruise),
            'reviews' => $this->fetchReviews($cruise),
            'services' => $services ?? [],
            'activities' => $activities ?? [],
            'double_room_price' => $double_room_price,
            'single_room_price' => $single_room_price,
            'travel_experiences' => $travel_experiences,
            'banner' => $banner,
            'gallery' => $gallery ?? [],
            'related_cruises' => $this->fetchRelatedCruises($cruise->related_cruises),
            'price_policy' => $policy->price_policy ?? "",
            'payment_policy' => $policy->payment_policy ?? "",
            'repeated_travellers' => $policy->repeated_travellers ?? "",
            'travel_schedule' => $policy->travel_schedule ?? "",
            'prices' => $prices ?? [],
            'seo' => [
                'title' => $seo_title,
                'keywords' => $seo_keywords,
                'robots' => $seo_robots,
                'description' => $seo_description,
                'facebook_description' => $facebook_description,
                'twitter_title' => $twitter_title,
                'facebook_title' => $facebook_title,
                'twitter_description' => $twitter_description,
                'twitter_image' => $twitter_image,
                'facebook_image' => $facebook_image,
            ],
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}
