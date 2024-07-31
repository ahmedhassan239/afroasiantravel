<?php

namespace App\Observers;


class SocialObserver
{
    public function saving()
    {
        cache()->delete('cache_social');

    }
    public function deleted()
    {
        cache()->delete('cache_social');

    }
}
