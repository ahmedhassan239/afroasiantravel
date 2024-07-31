<?php

namespace App\Http\Controllers;

use App\City;
use App\Destination;
use App\Package;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class FilterApiController extends Controller
{
    /* Package Filter */
    public function packageFilter($dest_id,$min_price,$max_price,$min_days, $max_days,$min_rate,$max_rate, $lang/*,$tour_type*/){

        $query =  Destination::select('destinations.id as destination__id','packages.id as package__id',
            'destinations.slug as destination_slug','packages.slug as package_slug',
            'destinations.name as destination_name','packages.name as package_name',
            'destinations.description as destination_description','packages.description as package_description',
            'destinations.alt as destination_alt','packages.thumb_alt as package_thumb_alt',
            'destinations._banner as destination_banner','packages._thumb as package_thumb',
            'packages.start_price','packages.location_package_map','packages.discount','packages.tour_type',
            'packages.days as duration_in_days','destinations.seo_title as destination_seo_title',
            'destinations.seo_keywords as destination_seo_keywords','destinations.seo_robots as destination_seo_robots',
            'destinations.twitter_title as destination_twitter_title','destinations.twitter_description as destination_twitter_description',
            'destinations.seo_description as destination_seo_description','destinations.facebook_description as destination_facebook_description',
            'destinations.twitter_image as destination_twitter_image','destinations.facebook_image as destinations_facebook_image',
            'packages.number_highlights','packages.itinerary','packages.meals','packages.accommodations','packages.flights',
            'packages.guide_tour','packages.hot_offer','packages.top_sale','packages.reviews','packages.rate'
        );
        $query->leftJoin('packages', 'destinations.id', '=', 'packages.destination_id');

        if(is_numeric($dest_id)){
            $query->where('destinations.id',$dest_id);
        }else{
            $query->whereRaw("(destinations.slug like '%".$dest_id."%')");
        }

        $query->where('destinations.status',1);
        $query->where('packages.status',1);
        $query->whereBetween('packages.start_price', [$min_price, $max_price]);
        $query->whereBetween('packages.days', [$min_days, $max_days]);
        $query->whereBetween('packages.rate', [$min_rate, $max_rate]);
        $destinations = $query->get();
        app()->setLocale($lang);
        $data= array();
        $dest= array();
        $packages= array();
        $seo= array();

        foreach($destinations as $destination){
            $destination__id = $destination->destination__id;
            $package__id= $destination->package__id;
            $destination_slug= $destination->destination_slug;
            $package_slug= $destination->package_slug;
            $destination_name= $destination->destination_name;
            $package_name= $destination->package_name;
            $destination_description= $destination->destination_description;
            $package_description= $destination->package_description;
            $destination_alt = $destination->destination_alt;
            $package_thumb_alt= $destination->package_thumb_alt;
            $destination_banner= asset('photos/' . $destination->destination_banner);
            $package_thumb= asset('photos/' . $destination->package_thumb);
            $start_price= $destination->start_price;
            $discount  = $destination->discount;
            $tour_type  =json_decode(json_decode($destination->tour_type,true));
            $location_package_map= $destination->location_package_map;
            $duration_in_days = $destination->duration_in_days;
            $destination_seo_title = $destination->destination_seo_title;
            $destination_seo_keywords = $destination->destination_seo_keywords;
            $destination_seo_robots = $destination->destination_seo_robots;
            $destination_seo_description = $destination->destination_seo_description;
            $destination_facebook_description = $destination->destination_facebook_description;
            $destination_twitter_title = $destination->destination_twitter_title;
            $destination_twitter_description = $destination->destination_twitter_description;
            $destination_twitter_image = $destination->destination_twitter_image;
            $destination_facebook_image = $destination->destination_facebook_image;
            $number_highlights  = $destination->number_highlights;
            $itinerary  = $destination->itinerary;
            $meals  = $destination->meals;
            $accommodations  = $destination->accommodations;
            $flights  = $destination->flights;
            $guide_tour  = $destination->guide_tour;
            if($destination->hot_offer == 1){
                $hot_offer  = true;
            }else{
                $hot_offer  = false;
            }

            if($destination->top_sale == 1){
                $top_sale  = true;
            }else{
                $top_sale  = false;
            }
            $rate  = $destination->rate;
            $reviews  = $destination->reviews;
            $dest= [
                'id'=>$destination__id,
                'slug'=>$destination_slug,
                'name'=>$destination_name,
                'description'=>$destination_description,
                'alt'=>$destination_alt,
                'banner'=>$destination_banner,
            ];
            $packages[] = [
                'id'=>$package__id,
                'slug'=>$package_slug,
                'name'=>$package_name,
                'description'=>$package_description,
                'thumb_alt'=>$package_thumb_alt,
                'thumb'=>$package_thumb,
                'start_price'=>$start_price,
                'discount'=>$discount,
                'tour_type'=> $tour_type,
                'location_package_map'=>$location_package_map,
                'duration_in_days'=>$duration_in_days,
                'number_highlights'=>$number_highlights,
                'itinerary'=>$itinerary,
                'meals'=>$meals,
                'accommodations'=>$accommodations,
                'flights'=>$flights,
                'guid_tour'=>$guide_tour,
                'hot_offer'=>$hot_offer,
                'top_sale'=>$top_sale,
                'reviews'=>$reviews,
                'rate'=>$rate,
            ];
            $seo= [
                'title'=>$destination_seo_title,
                'keywords'=>$destination_seo_keywords,
                'robots'=>$destination_seo_robots,
                'description'=>$destination_seo_description,
                'facebook_description'=>$destination_facebook_description,
                'twitter_title'=>$destination_twitter_title,
                'twitter_description'=>$destination_twitter_description,
                'twitter_image'=>$destination_twitter_image,
                'facebook_image'=>$destination_facebook_image,
            ];
        }
        return response()->json([
            'data'=>[
                'category' => [
                    'slug' => 'travel-packages',
                    'name' => 'Travel Packages',
                ],
                'destination'=>$dest,
                'packages'=>$packages,
                'seo'=>$seo,
            ]
        ],'200');
    }

    /* Excursions Filter*/
    public function excursionFilter ($dest_id,$min_price,$max_price,$min_days, $max_days,$min_rate,$max_rate,$city_id, $lang){
        $query =  Destination::select('cities.id as city_id','cities.name as city_name',
            'cities.slug as city_slug',
            'destinations.id as destination__id','excursions.id as excursion__id',
            'destinations.slug as destination_slug','excursions.slug as excursion_slug',
            'destinations.name as destination_name','excursions.name as excursion_name',
            'destinations.description as destination_description','excursions.description as excursion_description',
            'destinations.alt as destination_alt','excursions.thumb_alt as excursion_thumb_alt',
            'destinations._banner as destination_banner','excursions._thumb as excursion_thumb',
            'excursions.price_11','excursions.map_url','excursions.reviews','excursions.services_no','excursions.activities_no',
            'excursions.duration','excursions.travellers','excursions.guide_tour','excursions.rate','excursions.top_sale','excursions.discount',
            'destinations.seo_title as destination_seo_title','excursions.hot_offer','excursions.meals',
            'destinations.seo_keywords as destination_seo_keywords','destinations.seo_robots as destination_seo_robots',
            'destinations.twitter_title as destination_twitter_title','destinations.twitter_description as destination_twitter_description',
            'destinations.seo_description as destination_seo_description','destinations.facebook_description as destination_facebook_description',
            'destinations.twitter_image as destination_twitter_image','destinations.facebook_image as destinations_facebook_image'
        );
        $query->leftJoin('excursions', 'destinations.id', '=', 'excursions.destination_id');
        $query->leftJoin('cities', 'excursions.city_id', '=', 'cities.id');

        if(is_numeric($dest_id)){
            $query->where('destinations.id',$dest_id);
        }else{
            $query->whereRaw("(destinations.slug like '%".$dest_id."%')");
        }
        if(is_numeric($city_id)){
            $query->where('excursions.city_id',$city_id);
        }else{
            $query->whereRaw("(cities.slug like '%".$city_id."%')");
        }

        $query->where('destinations.status',1);
        $query->where('excursions.status',1);
        $query->whereBetween('excursions.price_11', [$min_price, $max_price]);
        $query->whereBetween('excursions.duration', [$min_days, $max_days]);
        $query->whereBetween('excursions.rate', [$min_rate, $max_rate]);
        $destinations = $query->get();
//        return $destinations;

        app()->setLocale($lang);


        $city= array();
        $destination= array();
        $excursions= array();
        $seo= array();

        foreach($destinations as $destination){
            $city_id = $destination->city_id;
            $city_name = $destination->city_name;
            $city_slug = $destination->city_slug;
            $destination__id = $destination->destination__id;
            $excursion__id= $destination->excursion__id;
            $destination_slug= $destination->destination_slug;
            $excursion_slug= $destination->excursion_slug;
            $destination_name= $destination->destination_name;
            $excursion_name= $destination->excursion_name;
            $destination_description= $destination->destination_description;
            $excursion_description= $destination->excursion_description;
            $destination_alt= $destination->destination_alt;
            $excursion_thumb_alt= $destination->excursion_thumb_alt;
            $destination_banner= asset('photos/' . $destination->destination_banner);
            $excursion_thumb= asset('photos/' . $destination->excursion_thumb);
            $start_price= $destination->price_11;

            $location_map= $destination->map_url;
            $duration = $destination->duration;
            $travellers = $destination->travellers;
            $destination_seo_title = $destination->destination_seo_title;
            $destination_seo_keywords = $destination->destination_seo_keywords;
            $destination_seo_robots = $destination->destination_seo_robots;
            $destination_seo_description = $destination->destination_seo_description;
            $destination_facebook_description = $destination->destination_facebook_description;
            $destination_twitter_title = $destination->destination_twitter_title;
            $destination_twitter_description = $destination->destination_twitter_description;
            $destination_twitter_image = $destination->destination_twitter_image;
            $destination_facebook_image  = $destination->destination_facebook_image;
            $reviews  = $destination->reviews;
            $meals  = $destination->meals;
            $rate = $destination->rate;
            $services_no  = $destination->services_no;
            $activities_no  = $destination->activities_no;
            $guide_tour  = $destination->guide_tour;
            $discount  = $destination->discount;
            if($destination->hot_offer == 1){
                $hot_offer  = true;
            }else{
                $hot_offer  = false;
            }

            if($destination->top_sale == 1){
                $top_sale  = true;
            }else{
                $top_sale  = false;
            }
            $destination =[
                'id'=>$destination__id,
                'slug'=>$destination_slug,
                'name'=>$destination_name,
                'description'=>$destination_description,
                'alt'=>$destination_alt,
                'banner'=>$destination_banner,
            ];
            $city = [
                'id'=>$city_id,
                'name'=>$city_name,
                'slug'=>$city_slug,
            ];
            $excursions[] = [
                'id'=>$excursion__id,
                'slug'=>$excursion_slug,
                'name'=>$excursion_name,
                'description'=>$excursion_description,
                'thumb_alt'=>$excursion_thumb_alt,
                'thumb'=>$excursion_thumb,
                'start_price'=>$start_price,
                'location_map'=>$location_map,
                'duration'=>$duration,
                'travellers'=>$travellers,
                'reviews'=>$reviews,
                'rate'=>$rate,
                'meals'=>$meals,
                'services_number'=>$services_no,
                'activities_number'=>$activities_no,
                'guide_tour'=>$guide_tour,
                'discount'=>$discount,
                'best_sale'=>$top_sale,
                'hot_offer'=>$hot_offer
            ];
            $seo =[
                'title'=>$destination_seo_title,
                'keywords'=>$destination_seo_keywords,
                'robots'=>$destination_seo_robots,
                'description'=>$destination_seo_description,
                'facebook_description'=>$destination_facebook_description,
                'twitter_title'=>$destination_twitter_title,
                'twitter_description'=>$destination_twitter_description,
                'twitter_image'=>$destination_twitter_image,
                'facebook_image'=>$destination_facebook_image,
            ];
        }
        return response()->json([
            'data'=>[
                'category' => [
                    'slug' => 'travel-cruises',
                    'name' => 'Travel Excursions',
                ],
                'destination'=>$destination,
                'city'=>$city,
                'excursions'=>$excursions,
                'seo'=>$seo,
            ]
        ],'200');
    }

    /* Cruises Filter */
    public function cruiseFilter ($dest_id,$min_price,$max_price,$min_days, $max_days,$min_rate,$max_rate, $lang){
        $query =  Destination::select('destinations.id as destination__id','cruises.id as cruise__id',
            'destinations.slug as destination_slug','cruises.slug as cruise_slug',
            'destinations.name as destination_name','cruises.name as cruise_name',
            'destinations.description as destination_description','cruises.description as cruise_description',
            'destinations.alt as destination_alt','cruises.thumb_alt as cruise_thumb_alt',
            'destinations._banner as destination_banner','cruises._thumb as cruise_thumb',
            'cruises.double_room_price','cruises.rate','cruises.days',
            'destinations.seo_title as destination_seo_title','cruises.location',
            'destinations.seo_keywords as destination_seo_keywords','destinations.seo_robots as destination_seo_robots',
            'destinations.twitter_title as destination_twitter_title','destinations.twitter_description as destination_twitter_description',
            'destinations.seo_description as destination_seo_description','destinations.facebook_description as destination_facebook_description',
            'destinations.twitter_image as destination_twitter_image','destinations.facebook_image as destinations_facebook_image',
            'cruises.reviews','cruises.itinerary','cruises.meals','cruises.accommodations','cruises.services_no'
            ,'cruises.activities_no','cruises.guide_tour','cruises.discount','cruises.top_sale','cruises.hot_offer');
        $query->leftJoin('cruises', 'destinations.id', '=', 'cruises.destination_id');
        if(is_numeric($dest_id)){
            $query->where('destinations.id',$dest_id);
        }else{
            $query->whereRaw("(destinations.slug like '%".$dest_id."%')");
        }
        $query->where('destinations.status',1);
        $query->where('cruises.status',1);
        $query->whereBetween('cruises.double_room_price', [$min_price, $max_price]);
        $query->whereBetween('cruises.days', [$min_days, $max_days]);
        $query->whereBetween('cruises.rate', [$min_rate, $max_rate]);
        $destinations = $query->get();

        app()->setLocale($lang);

        $destination= array();
        $cruises= array();
        $seo= array();
        foreach($destinations as $destination){
            $destination__id = $destination->destination__id;
            $cruise__id= $destination->cruise__id;
            $destination_slug= $destination->destination_slug;
            $cruise_slug= $destination->cruise_slug;
            $destination_name= $destination->destination_name;
            $cruise_name= $destination->cruise_name;
            $destination_description= $destination->destination_description;
            $cruise_description= $destination->cruise_description;
            $destination_alt= $destination->destination_alt;
            $cruise_thumb_alt= $destination->cruise_thumb_alt;
            $destination_banner= asset('photos/' . $destination->destination_banner) ;
            $cruise_thumb= asset('photos/' . $destination->cruise_thumb);
            $start_price= $destination->double_room_price;
            $duration_in_days = $destination->days;
            $rate = $destination->rate;
            $location = $destination->location;
            $destination_seo_title = $destination->destination_seo_title;
            $destination_seo_keywords = $destination->destination_seo_keywords;
            $destination_seo_robots = $destination->destination_seo_robots;
            $destination_seo_description = $destination->destination_seo_description;
            $destination_facebook_description = $destination->destination_facebook_description;
            $destination_twitter_title = $destination->destination_twitter_title;
            $destination_twitter_description = $destination->destination_twitter_description;
            $destination_twitter_image = $destination->destination_twitter_image;
            $destination_facebook_image  = $destination->destination_facebook_image;
            $reviews  = $destination->reviews;
            $itinerary  = $destination->itinerary;
            $meals  = $destination->meals;
            $accommodations  = $destination->accommodations;
            $services_no  = $destination->services_no;
            $activities_no  = $destination->activities_no;
            $guide_tour  = $destination->guide_tour;
            $discount  = $destination->discount;
            if($destination->hot_offer == 1){
                $hot_offer  = true;
            }else{
                $hot_offer  = false;
            }

            if($destination->top_sale == 1){
                $top_sale  = true;
            }else{
                $top_sale  = false;
            }
            $destination = [
                'id'=>$destination__id,
                'slug'=>$destination_slug,
                'name'=>$destination_name,
                'description'=>$destination_description,
                'alt'=>$destination_alt,
                'banner'=>$destination_banner,
            ];
            $cruises[] = [

                'id'=>$cruise__id,
                'slug'=>$cruise_slug,
                'name'=>$cruise_name,
                'description'=>$cruise_description,
                'thumb_alt'=>$cruise_thumb_alt,
                'thumb'=>$cruise_thumb,
                'start_price'=>$start_price,
                'duration_in_days'=>$duration_in_days,
                'rate'=>$rate,
                'location'=>$location,
                'reviews'=>$reviews,
                'itinerary'=>$itinerary,
                'meals'=>$meals,
                'accommodations'=>$accommodations,
                'services_number'=>$services_no,
                'activities_number'=>$activities_no,
                'guide_tour'=>$guide_tour,
                'discount'=>$discount,
                'best_sale'=>$top_sale,
                'hot_offer'=>$hot_offer
            ];
            $seo =[
                'title'=>$destination_seo_title,
                'keywords'=>$destination_seo_keywords,
                'robots'=>$destination_seo_robots,
                'description'=>$destination_seo_description,
                'facebook_description'=>$destination_facebook_description,
                'twitter_title'=>$destination_twitter_title,
                'twitter_description'=>$destination_twitter_description,
                'twitter_image'=>$destination_twitter_image,
                'facebook_image'=>$destination_facebook_image,
            ];
        }
        return response()->json([
            'data'=>[
                'category' => [
                    'slug' => 'travel-cruises',
                    'name' => 'Travel Cruises',
                ],
                'destination'=>$destination,
                'cruises'=>$cruises,
                'seo'=>$seo,
            ]
        ],'200');
    }


    

}
