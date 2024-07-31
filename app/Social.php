<?php

namespace App;

use App\Observers\SocialObserver;
use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Social extends Model
{
    public $table = "socials";
    use IsTranslatable;
    public $translatable = ['address1','address2'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SocialObserver::class);
    }
}
