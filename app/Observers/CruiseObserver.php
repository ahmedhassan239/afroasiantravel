<?php

namespace App\Observers;

use App\Cruise;

class CruiseObserver
{
    public function saving(Cruise $cruise)
    {
        cache()->delete('cache_cruise');
        cache()->delete('cache_cruise_'.$cruise->id);
        cache()->delete('cache_cruise_featured_'.$cruise->id);
    }

    public function deleted(Cruise $cruise)
    {
        cache()->delete('cache_cruise_'.$cruise->id);
        cache()->delete('cache_cruise_featured_'.$cruise->id);
    }
}
