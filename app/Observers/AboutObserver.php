<?php

namespace App\Observers;

use App\About;
use BaseObserver;
use Illuminate\Support\Facades\Cache;


class AboutObserver //extends BaseObserver
{
    public function saving(About $about)
    {
//        cache()->delete('cache_about');
        Cache::flush();
        \cache()->flush();
    }
    public function deleted(About $about)
    {
        cache()->delete('cache_about');
    }


}
