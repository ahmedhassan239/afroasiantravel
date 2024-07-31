<?php

namespace App;

use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use IsTranslatable;
    public $table = "options";
    public $translatable = ['content'];

    public function package()
    {
        return $this->belongsTo(Package::class);
//        return $this->hasMany(Package::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
}

