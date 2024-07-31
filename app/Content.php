<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Content extends Model
{
    public $table = "content";
    use HasTranslations;
    public $translatable = ['name','slug','thumb_alt'];
}
