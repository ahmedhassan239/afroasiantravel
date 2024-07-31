<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Category;
use App\Contact;
use App\Cruise;
use App\Destination;
use App\EmailSubscription;
use App\Enquiry;
use App\Excursion;
use App\Faq;
use App\Footer;
use App\Hotel;
use App\Incentive;
use App\LangControl;
use App\Mail\AppointmentDetailsToAdmin;
use App\Package;
use App\Page;
use App\SidePhoto;
use App\TravelGuide;
use App\Setting;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MainApiController extends Controller
{
    public function getSearch($name, $lang)
    {
        app()->setLocale($lang);
    
        $blogs = Blog::with('destination:id,slug')->with('destination')->where('name', 'like', "%" . $name . "%")->where('status', 1)->get();
        $blog_data = array();
        foreach ($blogs as $blog) {
            $blog_data[] = [
                'destination' => [
                    'slug' => $blog->destination->slug ?? []
                ],
                'type' => 'blogs',
                'id' => $blog->id,
                'name' => $blog->name,
                'slug' => $blog->slug
            ];
        }
        $packages = Package::where('name', 'like', "%" . $name . "%")->with('destination')->where('status', 1)->get();
        $package_data = array();
        foreach ($packages as $package) {
            $package_data[] = [
                'destination' => [
                    'slug' => $package->destination->slug ?? []
                ],
                'type' => 'packages',
                'id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
            ];
        }
        
        $excursions = Excursion::where('name', 'like', "%" . $name . "%")->with('city', 'destination')->where('status', 1)->get();
        $excursion_data = array();
        foreach ($excursions as $excursion) {
            $excursion_data[] = [
                'city' => [
                    'slug' => $excursion->city->slug ?? []
                ],
                'destination' => [
                    'slug' => $excursion->destination->slug ?? []
                ],
                'type' => 'excursions',
                'id' => $excursion->id,
                'name' => $excursion->name,
                'slug' => $excursion->slug,
            ];
        }
        

        $data = [
           
            'blogs' => $blog_data,
            'package' => $package_data,
            'excursion' => $excursion_data,
        ];
        return response()->json([
            'data' => $data
        ], '200');
    }

    /*Footer Api*/
    public function getFooters($id, $lang)
    {

        //   $data =  cache()->rememberForever('cache_footer_'.$id,  function () use ($id,$lang){

        $footers = Footer::whereHas('destination', function ($query) use ($id) {
            $query->where('id', $id)->orWhere('slug->en', $id);
        })->get()->map(function ($val) {
            return [
                'id' => $val->id,
                'title' => $val->title,
                'destination_name' => $val->destination->name,
                'destination_slug' => $val->destination->slug,
                'categories' => $val->categories_list,
            ];
        });

        return response()->json([
            'data' => $footers
        ], '200');
    }

    /*Side Photos*/
    // 
    public function getSidePhotos($dest_id, $module)
    {
        $side_photos = SidePhoto::whereHas('destination', function ($query) use ($dest_id) {
            $query->where('id', $dest_id);
            $query->orWhere('slug->en', $dest_id);
        })->where('module', '=', $module)
            ->orderBy('sort_order', 'desc')
            ->get()->map(function ($value) {
                return [
                    'destination' => [
                        'slug' => $value->destination->slug ?? []
                    ],
                    'id' => $value->id,
                    'link' => $value->link,
                    'module' => $value->module,
                    'image_alt' => $value->image_alt,
                    'image' =>asset('photos/' . $value->large_img)
                ];
            });
        return response()->json([
            'data' => $side_photos
        ], '200');
    }

    /*Language Control*/
    public function getLangControl()
    {

        $langs = LangControl::get()->map(function ($val) {
            return [
                'english' => $val->english == 1,
                'french' => $val->french == 1,
                'spanish' => $val->spanish == 1,
                'deutsch' => $val->deutsch == 1,
                'russian' => $val->russian == 1,
                'italian' => $val->italian == 1,
            ];
        });

        return response()->json([
            'data' => $langs
        ], '200');
    }
    /* Subscription Email */
    public function insertEmail(Request $request){
        $validator =Validator::make($request->all(), [
             'email' => 'required|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            $data = EmailSubscription::create([
                'email'=> $request->email,
            ]);
        }

        return response()->json([
            'data'=>$data,
            'success' => 'success'
        ],'200');
    }
    public function enquiryForm(Request $request){
        $validator =Validator::make($request->all(), [
             'email' => 'required|email:rfc,dns',
             'date' => 'required',
             'package_name' => 'required',
             'name' => 'required|min:2',
             'phone' => 'required|min:10',
             'adult' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            $enquiry = Enquiry::create([
                'email'=> $request->email,
                'date'=> $request->date,
                'name'=> $request->name,
                'phone'=> $request->phone,
                'adult'=> $request->adult,
                'child'=> $request->child,
                'room_type'=> $request->room_type,
                'package_name'=> $request->package_name,
            ]);
        }
        Mail::to('info@afroasiantravel.com')->send(new AppointmentDetailsToAdmin($enquiry));
        return response()->json([
            'data'=>$enquiry,
            'success' => 'success'
        ],'200');
    }
   
    public function contactUsForm(Request $request){
        $validator =Validator::make($request->all(), [
             'email' => 'required|email:rfc,dns',
             'first_name' => 'required|min:2',
             'last_name' => 'required|min:2',
             'phone' => 'required|min:10',
             'message' => 'required',
          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            $data = Contact::create([
                'email'=> $request->email,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'phone'=> $request->phone,
                'message'=> $request->message,
            ]);
        }

        return response()->json([
            'data'=>$data,
            'success' => 'success'
        ],'200');
    }


    public function incentiveForm(Request $request){
        $validator =Validator::make($request->all(), [
             'email' => 'required|email:rfc,dns',
             'first_name' => 'required|min:2',
             'last_name' => 'required|min:2',
             'phone' => 'required|min:10',
             'message' => 'required',
          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            $data = Incentive::create([
                'email'=> $request->email,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'phone'=> $request->phone,
                'message'=> $request->message,
            ]);
        }

        return response()->json([
            'data'=>$data,
            'success' => 'success'
        ],'200');
    }
    public function settings(){

        $data = Setting::first();
        return response()->json([
            'data'=>$data,
            'success' => 'success'
        ],'200');
    }
}
