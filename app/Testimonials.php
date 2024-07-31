<?php

namespace App;

use App\Observers\TestimonialObserver;
use Illuminate\Database\Eloquent\Model;

class Testimonials extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TestimonialObserver::class);
    }
}
