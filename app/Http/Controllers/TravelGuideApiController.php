<?php

namespace App\Http\Controllers;

use App\Destination;
use ClassicO\NovaMediaLibrary\API;

class TravelGuideApiController extends Controller
{
    public function getSingleTravelGuide($dest_id, $id)
    {
        $dest = Destination::where('id', $dest_id)
            ->orWhere('slug->en', $dest_id)
            ->WhereHas('travelGuides', function ($q) use ($id) {
                $q->where('id', $id)
                    ->orWhere('slug->en', $id);
            })->with('travelGuides')
            ->firstOrFail();

        $value = $dest->travelGuides->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });

        $data[] = [
            'destination' => [
                'id' => $value->destination->id,
                'slug' => $value->destination->slug,
                'name' => $value->destination->name,
            ],
            'category' => [
                'slug' => 'travel-guides',
                'name' => 'Travel Guides',
                ],
            'id' => $value->id,
            'name' => $value->name,
            'slug' => $value->slug,
            'description' => $value->description,
            'overview' => $value->overview,
            'thumb_alt' => $value->thumb_alt,
            'thumb' => asset('photos/' . $value->_thumb),//API::getFiles($value->thumb),//asset('photos/' . $value->_thumb)
            'banner_alt' => $value->alt,
            'banner' => asset('photos/' . $value->_banner),//API::getFiles($value->banner),//
            // 'gallery' => API::getFiles($value->gallery_list,$imgSize = null, $object = true)?? [],
            'image_over_banner' => API::getFiles($value->image_over_banner),
            'related_travel_guides' => $value->related_travel_guide_list ?? [],
            'related_pages' => $value->related_pages_list ?? [],
            'related_packages' => $value->related_packages_list ?? [],
            'seo' => [
                'title' => $value->seo_title,
                'keywords' => $value->seo_keywords,
                'robots' => $value->seo_robots,
                'description' => $value->seo_description,
                'facebook_description' => $value->facebook_description,
                'twitter_title' => $value->twitter_title,
                'twitter_description' => $value->twitter_description,
                'facebook_title' => $value->og_title,
                'twitter_image' =>asset('photos/' . $value->_twitter_image), //API::getFiles($value->twitter_image),
                'facebook_image' => asset('photos/' . $value->_facebook_image),//API::getFiles($value->facebook_image),

            ]
        ];


        return response()->json([
            'data' => $data
        ], '200');
    }
}
