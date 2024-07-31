<?php

namespace App\Http\Controllers;
use App\Blog;
use App\Destination;
use Illuminate\Support\Carbon;

class BlogApiController extends Controller
{
    /*Single Blog Apis*/
    public function getSingleBlog($id)
    {
        $lang = app()->getLocale();  // Get the current language

        $dest = Destination::
            WhereHas('blogs', function ($q) use ($id, $lang) {
                $q->where('id', $id)
                  ->orWhere("slug->$lang", $id);
            })->with('blogs')
            ->firstOrFail();
    
        $val = $dest->blogs->first(function ($el) use ($id) {
            return $el->id == $id || $el->slug === $id;
        });

        $blog[] = [
                    'destination'=>[
                        'id'=>$val->destination->id,
                        'slug'=>$val->destination->slug,
                        'name'=>$val->destination->name,
                    ],
                    'id'=>$val->id,
                    'name'=>$val->name,
                    'slug'=> $val->slug,
                    'overview'=>$val->overview,
                    'thumb_alt' => $val->thumb_alt,
                    'banner_alt'=>$val->alt,
                    'thumb' => asset('photos/' . $val->_thumb),//API::getFiles($val->thumb),
                    'banner'=>asset('photos/' . $val->_banner),//API::getFiles($val->banner),
                    'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->isoFormat('MMM Do YY') ,
                    'related_blogs'=> $val->related_blogs_list ?? [],
                    'related_packages'=>$val->related_packages_list ?? [],
                    'seo'=>[
                        'title'=>$val->seo_title,
                        'keywords'=>$val->seo_keywords,
                        'robots'=>$val->seo_robots,
                        'description'=>$val->seo_description,
                        'facebook_title'=>$val->og_title,
                        'facebook_description'=>$val->facebook_description,
                        'twitter_title'=>$val->twitter_title,
                        'twitter_description'=>$val->twitter_description,
                        'twitter_image'=>asset('photos/' . $val->_twitter_image),//API::getFiles($val->twitter_image),
                        'facebook_image'=>asset('photos/' . $val->_facebook_image),//API::getFiles($val->facebook_image),
                    ],
                ];

        return response()->json([
            'blog'=>$blog,
        ]);
    }

    /*Home Pages Blogs*/

    public function getFeaturedBlogs($id){
            $query = Blog::where('status',1)
                ->where('featured', 1)
                ->with('destination')->get();
                $data =  $query->map(function ($value){
                    return[
                        'destination'=>[
                            'slug'=>$value->destination->slug,
                        ],
                        'id'=>$value->id,
                        'name'=>$value->name,
                        'slug'=> $value->slug,
                        'description'=>$value->description,
                        'thumb_alt' => $value->thumb_alt,
                        'thumb' => asset('photos/' . $value->_thumb),//API::getFiles($value->thumb),
                    ];
                });
             

            return response()->json([
                'blog'=>[
                    'data' => $data,
                ]
            ],'200');
    }
}
