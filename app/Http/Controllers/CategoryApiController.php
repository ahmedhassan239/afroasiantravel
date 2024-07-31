<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Cruise;
use App\Destination;
use App\Excursion;
use App\Package;
use Illuminate\Pagination\LengthAwarePaginator;

use ClassicO\NovaMediaLibrary\API;


class CategoryApiController extends Controller
{
    public function getSingleDestinationCategories($dest_id, $lang)
    {

        $query = Destination::with(['categories' => function ($x) {
            $x->where('status', 1)->where('showed', 1);
        }])->where('id', $dest_id)->orWhere('slug->en', $dest_id)->get();


        $data = $query->map(function ($val) {
            return [
                'categories' => $val['categories']->map(function ($value) use ($val) {
                    return [
                        'id' => $value->id,
                        'name' => $value->name,
                        'slug' => $value->slug,
                        'title' => $value->title,
                        'description' => $value->description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),
                    ];
                }),
                'destination' => [
                    'banner' => asset('photos/' . $val->_banner),
                    'slug' => $val->slug,
                ],

            ];
        });
        //+
        return response()->json([
            'data' => $data
        ], '200');
    }



    public function destinationHotOffer($dest_id, $lang)
    {
        $packages = Package::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })
            ->where('status', '=', 1)
            ->where('hot_offer', '=', 1)

            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });

        $excursions = Excursion::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })
            ->where('status', 1)
            ->where('hot_offer', 1)

            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'city' => [
                        'slug' => $value->city->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });

        $cruises = Cruise::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })
            ->where('status', 1)
            ->where('hot_offer', 1)
            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => asset('photos/' . $value->_thumb)
                ];
            });

        $data[] = [

            'packages' => $packages ?? [],
            'excursions' => $excursions ?? [],
            'cruises' => $cruises ?? [],
        ];

        return response()->json([
            'data' => $data
        ], '200');
    }

    public function singleCategory($id)
    {
        $lang = app()->getLocale(); 
        $query = Category::where(function ($query) use ($id,$lang) {
            $query->where('id', $id)
                ->orWhere("slug->$lang", $id);
        })->where('status', 1)->get();
        $data = $query->map(function ($val) {
            return [
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'description' => $val->description,
                'banner_alt' => $val->alt,
                'banner' => asset('photos/' . $val->_banner),
                'seo' => [
                    'title' => $val->seo_title,
                    'keywords' => $val->seo_keywords,
                    'robots' => $val->seo_robots,
                    'description' => $val->seo_description,
                    'facebook_description' => $val->facebook_description,
                    'twitter_title' => $val->twitter_title,
                    'twitter_description' => $val->twitter_description,
                    'facebook_title' => $val->og_title,
                    'twitter_image' => asset('photos/' . $val->_twitter_image),
                    'facebook_image' => asset('photos/' . $val->_facebook_image),
                ]
            ];
        });

        return response()->json([
            'data' => $data
        ], '200');
    }

    public function getPackagesWhereService($category_id)
    {
        $lang = app()->getLocale(); 
        $category = Category::where('id', $category_id)->orWhere("slug->$lang", $category_id)->first();
    
        if ($category) {
            $filteredPackages = Package::all()->filter(function ($package) use ($category) {
                return in_array($category->id, json_decode($package->category, true));
            })->values();
    
            $paginatedPackages = new LengthAwarePaginator(
                $filteredPackages->forPage(LengthAwarePaginator::resolveCurrentPage(), 9),
                $filteredPackages->count(),
                9,
                LengthAwarePaginator::resolveCurrentPage(),
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
    
            $packages = $paginatedPackages->map(function ($val) {
                return [
                    'destination' => [
                        'id' => $val['destination']['id'],
                        'slug' => $val['destination']['slug'],
                        'name' => $val['destination']['name'],
                    ],
                    'id' => $val->id,
                    'slug' => $val->slug,
                    'name' => $val->name,
                    'description' => $val->description,
                    'thumb_alt' => $val->thumb_alt,
                    'thumb' => asset('photos/' . $val->_thumb),
                    'location_map' => $val->location_package_map,
                    'duration_in_days' => $val->days,
                    'services' => Category::whereIn('id', json_decode($val->category, true))->pluck('name')->toArray() ?? [],
                    'start_price' => $val->start_price,
                    'price_after_discount' => $val->discount,
                    'Popular' => $val->hot_offer == 1,
                    'rate' => $val->rate,
                    'accessibility' => $val->accessibility == true,
                    'location' => $val->location_icon == true,
                    'tour_guide' => $val->tour_guide == true,
                    'schedule' => $val->schedule == true,
                    'transportation' => json_decode($val->transportation, true)?? [],
                    'languages' => json_decode($val->languages, true) ??[],
                ];
            });
    
            return response()->json([
                'data' => [
                    'paginator' => [
                        'perPage' => $paginatedPackages->perPage(),
                        'currentPage' => $paginatedPackages->currentPage(),
                        'total' => $paginatedPackages->total(),
                        'lastPage' => $paginatedPackages->lastPage(),
                    ],
                    'packages' => $packages,
                ]
            ], 200);
        }
    
        return response()->json(['message' => 'No matching category found'], 404);
    }
    

    public function getAllServices(){
        $services = Category::get();

        $data = $services->map(function ($val) {
            return [
                'id' => $val->id,
                'name' => $val->name,
                'slug' => $val->slug,
                'short_description' => $val->short_description,
                'icon' => $val->icon,
                'thumb' => asset('photos/' . $val->_thumb),
                'thumb_alt' => $val->thumb_alt,
            ];
        });

        return response()->json([
            'data' => $data
        ], '200');
    }
}
