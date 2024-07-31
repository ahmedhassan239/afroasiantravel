<?php

namespace App\Observers;


class SliderObserver
{
   public function saving(){
       cache()->delete('cache_slider');
   }

    public function deleted()
    {
        cache()->delete('cache_slider');
    }
}
