<?php

namespace App;

use App\Observers\GuideObserver;
use App\Traits\IsTranslatable;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Guide extends Model
{
//    use HasTranslations;
      use IsTranslatable;
    public $table = "guides";
    public $translatable =['description'];
    protected $casts = [
        'description' => 'array',
        ];

    protected static function boot()
    {
        parent::boot();

        static::observe(GuideObserver::class);
    }

    public function getPhotoAttribute($value){
        return  API::getFiles($value);
    }
}
