<?php

namespace App\Observers;

use App\Guide;

class GuideObserver
{
    public function saving(Guide $guide)
    {
        cache()->delete('cache_guide');
    }
    public function deleted(Guide $guide)
    {
        cache()->delete('cache_guide');
    }

}
