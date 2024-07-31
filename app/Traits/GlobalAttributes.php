<?php

namespace App\Traits;

use ClassicO\NovaMediaLibrary\API;

trait GlobalAttributes

{
    public function getBannerAltAttribute(){
       return API::getFiles($this->attributes['banner']);
    }
    public function getThumbAttribute($value){
        return API::getFiles($value);
    }

    public function getBannerAttribute($value){
        return API::getFiles($value);
    }

    public function getImageOverBannerAttribute($value){
        return API::getFiles($value);
    }

//    public function getBannerAttribute($value){
//        return API::getFiles($value);
//    }

}

