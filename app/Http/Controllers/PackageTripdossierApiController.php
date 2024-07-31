<?php

namespace App\Http\Controllers;

use App\Package;
use App\PackageTripdossier;
use Illuminate\Http\Request;
use ClassicO\NovaMediaLibrary\API;
use App\Mail\PackageDossierInEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PackageTripdossierApiController extends Controller
{
    public function storePackageTripdossier($id,Request $request){
        $validator =Validator::make($request->all(), [
            // 'name' => 'required|min:5',
            // 'email' => 'required|email:rfc,dns',
            // 'phone'=>'required|min:10',
            // 'comment'=>'required|min:10'
        ]);

        //        dd($validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            $data = PackageTripdossier::create([
                'name' => $request->name,
                'package_id'=> $id,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'comment'=> $request->comment,
            ]);


             $package = Package::find($id);
             //trip dossier file
            $tripdossier = API::getFiles($package->trip_dossier_file, $imgSize = null, $object = false);

            $email=$request->email;
//            dd($email);
            $sendmail = new PackageDossierInEmail($tripdossier,$email);
//             dd($sendmail);
            Mail::send($sendmail);

//            dd(Mail::to('ahmed25.ah@gmail.com')->send($sendmail));

//            Mail::send('package.mail',$package->toArray(),function($message){
//                $message->to('donnamikahil1@gmail.com','test')
//                    ->subject('only for test');
//            });


            return response()->json([
                'data'=>$data,
                'package'=>$package,
                'tripdossier'=>$tripdossier,
                'success' => 'success'
            ],'200');
        }

    }

    public function EmailPackageTripdossier()
    {
        foreach($package_emails as $item){
            if($item->trip_dossier_file != null){
                // return API::getFiles($item->trip_dossier_file, $imgSize = null, $object = false);
                $data=$item->trip_dossier_file;
                $email=$item->email;
                $sendmail = new PackageDossierInEmail($data,$email);
                Mail::to($item->email)->send($sendmail);
            }
        }
    }
}
