<?php

namespace App\Http\Controllers;

use App\Content;
use App\Cruise;
use App\Destination;
use App\Excursion;
use App\Package;
use App\Page;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Http\Request;

class DestinationContentApiController extends Controller
{
    public function getPageEnContent($dest_id)
    {
        $page_contents = Content::get();

        app()->setLocale('en');

        $data = array();

        $page_contents = $page_contents->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
            ];
        });

        $destination = Destination::where('status',1)->find($dest_id);
        if ($destination->related_pages != Null) {
            $related_pages =  Page::whereIn('id',json_decode($destination->related_pages))->get();

            $related_pages = $related_pages->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $related_pages = [];
        }
        if ($destination->packages_hot_offers != Null) {
            $packages_hot_offers = Package::whereIn('id',json_decode($destination->packages_hot_offers))->get();

            $packages_hot_offers = $packages_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $packages_hot_offers = [];
        }
        if ($destination->excursions_hot_offers != Null) {
            $excursions_hot_offers = Excursion::whereIn('id',json_decode($destination->excursions_hot_offers))->get();
            $excursions_hot_offers = $excursions_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $excursions_hot_offers = [];
        }
        if ($destination->cruises_hot_offers != Null) {
            $cruises_hot_offers = Cruise::whereIn('id',json_decode($destination->cruises_hot_offers))->get();
            $cruises_hot_offers = $cruises_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $cruises_hot_offers = [];
        }

        $data[]=[
            'page_content'=>$page_contents,
            'related_pages'=>$related_pages,
            'packages_hot_offers'=>$packages_hot_offers,
            'excursions_hot_offers'=>$excursions_hot_offers,
            'cruises_hot_offers'=>$cruises_hot_offers,
            ];
        return response()->json([
            'data'=>$data
        ],'200');

    }

    public function getPageFrContent($dest_id)
    {
        $page_contents = Content::get();

        app()->setLocale('fr');

        $data = array();

        $page_contents = $page_contents->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
            ];
        });

        $destination = Destination::where('status',1)->find($dest_id);
        if ($destination->related_pages != Null) {
            $related_pages =  Page::whereIn('id',json_decode($destination->related_pages))->get();

            $related_pages = $related_pages->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $related_pages = [];
        }
        if ($destination->packages_hot_offers != Null) {
            $packages_hot_offers = Package::whereIn('id',json_decode($destination->packages_hot_offers))->get();

            $packages_hot_offers = $packages_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $packages_hot_offers = [];
        }
        if ($destination->excursions_hot_offers != Null) {
            $excursions_hot_offers = Excursion::whereIn('id',json_decode($destination->excursions_hot_offers))->get();
            $excursions_hot_offers = $excursions_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $excursions_hot_offers = [];
        }
        if ($destination->cruises_hot_offers != Null) {
            $cruises_hot_offers = Cruise::whereIn('id',json_decode($destination->cruises_hot_offers))->get();
            $cruises_hot_offers = $cruises_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $cruises_hot_offers = [];
        }

        $data[]=[
            'page_content'=>$page_contents,
            'related_pages'=>$related_pages,
            'packages_hot_offers'=>$packages_hot_offers,
            'excursions_hot_offers'=>$excursions_hot_offers,
            'cruises_hot_offers'=>$cruises_hot_offers,
            ];
        return response()->json([
            'data'=>$data
        ],'200');

    }

    public function getPageEsContent($dest_id)
    {
        $page_contents = Content::get();

        app()->setLocale('es');

        $data = array();

        $page_contents = $page_contents->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
            ];
        });

        $destination = Destination::where('status',1)->find($dest_id);
        if ($destination->related_pages != Null) {
            $related_pages =  Page::whereIn('id',json_decode($destination->related_pages))->get();

            $related_pages = $related_pages->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $related_pages = [];
        }
        if ($destination->packages_hot_offers != Null) {
            $packages_hot_offers = Package::whereIn('id',json_decode($destination->packages_hot_offers))->get();

            $packages_hot_offers = $packages_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $packages_hot_offers = [];
        }
        if ($destination->excursions_hot_offers != Null) {
            $excursions_hot_offers = Excursion::whereIn('id',json_decode($destination->excursions_hot_offers))->get();
            $excursions_hot_offers = $excursions_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $excursions_hot_offers = [];
        }
        if ($destination->cruises_hot_offers != Null) {
            $cruises_hot_offers = Cruise::whereIn('id',json_decode($destination->cruises_hot_offers))->get();
            $cruises_hot_offers = $cruises_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $cruises_hot_offers = [];
        }

        $data[]=[
            'page_content'=>$page_contents,
            'related_pages'=>$related_pages,
            'packages_hot_offers'=>$packages_hot_offers,
            'excursions_hot_offers'=>$excursions_hot_offers,
            'cruises_hot_offers'=>$cruises_hot_offers,
            ];
        return response()->json([
            'data'=>$data
        ],'200');

    }

    public function getPageDeContent($dest_id)
    {
        $page_contents = Content::get();

        app()->setLocale('de');

        $data = array();

        $page_contents = $page_contents->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
            ];
        });

        $destination = Destination::where('status',1)->find($dest_id);
        if ($destination->related_pages != Null) {
            $related_pages =  Page::whereIn('id',json_decode($destination->related_pages))->get();

            $related_pages = $related_pages->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $related_pages = [];
        }
        if ($destination->packages_hot_offers != Null) {
            $packages_hot_offers = Package::whereIn('id',json_decode($destination->packages_hot_offers))->get();

            $packages_hot_offers = $packages_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $packages_hot_offers = [];
        }
        if ($destination->excursions_hot_offers != Null) {
            $excursions_hot_offers = Excursion::whereIn('id',json_decode($destination->excursions_hot_offers))->get();
            $excursions_hot_offers = $excursions_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $excursions_hot_offers = [];
        }
        if ($destination->cruises_hot_offers != Null) {
            $cruises_hot_offers = Cruise::whereIn('id',json_decode($destination->cruises_hot_offers))->get();
            $cruises_hot_offers = $cruises_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $cruises_hot_offers = [];
        }

        $data[]=[
            'page_content'=>$page_contents,
            'related_pages'=>$related_pages,
            'packages_hot_offers'=>$packages_hot_offers,
            'excursions_hot_offers'=>$excursions_hot_offers,
            'cruises_hot_offers'=>$cruises_hot_offers,
            ];
        return response()->json([
            'data'=>$data
        ],'200');

    }

    public function getPageRuContent($dest_id)
    {
        $page_contents = Content::get();

        app()->setLocale('ru');

        $data = array();

        $page_contents = $page_contents->map(function ($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'slug' => $value->slug,
                'thumb_alt' => $value->thumb_alt,
                'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
            ];
        });

        $destination = Destination::where('status',1)->find($dest_id);
        if ($destination->related_pages != Null) {
            $related_pages =  Page::whereIn('id',json_decode($destination->related_pages))->get();

            $related_pages = $related_pages->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $related_pages = [];
        }
        if ($destination->packages_hot_offers != Null) {
            $packages_hot_offers = Package::whereIn('id',json_decode($destination->packages_hot_offers))->get();

            $packages_hot_offers = $packages_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $packages_hot_offers = [];
        }
        if ($destination->excursions_hot_offers != Null) {
            $excursions_hot_offers = Excursion::whereIn('id',json_decode($destination->excursions_hot_offers))->get();
            $excursions_hot_offers = $excursions_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $excursions_hot_offers = [];
        }
        if ($destination->cruises_hot_offers != Null) {
            $cruises_hot_offers = Cruise::whereIn('id',json_decode($destination->cruises_hot_offers))->get();
            $cruises_hot_offers = $cruises_hot_offers->map(function ($value) {
                return [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'thumb_alt' => $value->thumb_alt,
                    'thumb' => API::getFiles($value->thumb, $imgSize = null, $object = false)
                ];
            });
        }else{
            $cruises_hot_offers = [];
        }

        $data[]=[
            'page_content'=>$page_contents,
            'related_pages'=>$related_pages,
            'packages_hot_offers'=>$packages_hot_offers,
            'excursions_hot_offers'=>$excursions_hot_offers,
            'cruises_hot_offers'=>$cruises_hot_offers,
            ];
        return response()->json([
            'data'=>$data
        ],'200');

    }
}
