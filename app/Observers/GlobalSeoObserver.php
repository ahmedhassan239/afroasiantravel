<?php

namespace App\Observers;

use App\GlobalSeo;

class GlobalSeoObserver
{
    public function saving()
    {
        cache()->delete('cache_global_seo');
    }
    public function deleted()
    {
        cache()->delete('cache_global_seo');
    }
}
