<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('nova.login');
});


Route::get('/optimize', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    return "Cache, Route & Config is cleared";
});

Route::get('/zzz','AboutApiController@zzz');



 Route::get("/storage-link", function () {
     $targetFolder = storage_path("app/public");
     $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
     symlink($targetFolder, $linkFolder);

     return "done";
 });
