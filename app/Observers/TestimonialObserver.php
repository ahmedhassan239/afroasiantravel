<?php

namespace App\Observers;



class TestimonialObserver
{
    public function saving()
    {

        cache()->delete('cache_testimonial');
    }

    public function deleted()
    {
        cache()->delete('cache_testimonial');
    }
}
