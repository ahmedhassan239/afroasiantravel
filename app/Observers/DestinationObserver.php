<?php

namespace App\Observers;

use App\Destination;

class DestinationObserver
{
    public function saving(Destination $destination)
    {
        cache()->delete('cache_all_destination');
       // cache()->delete('cache_destination_'.$destination->id);
        cache()->delete('cache_blog_featured_'.$destination->id);

    }

    public function deleted(Destination $destination)
    {
        cache()->delete('cache_all_destination');
        cache()->delete('cache_blog_featured_'.$destination->id);
      //  cache()->delete('cache_destination_'.$destination->id);


    }
}
