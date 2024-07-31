<?php

namespace App\Observers;

use App\TravelGuide;

class TravelGuideObserver
{
    public function saving(TravelGuide $travel)
    {
        cache()->delete('cache_travel_guid'.$travel->id);
    }
    public function deleted(TravelGuide $travel)
    {
        cache()->delete('cache_travel_guid'.$travel->id);
    }
}
