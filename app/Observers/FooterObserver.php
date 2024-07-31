<?php

namespace App\Observers;

use App\Footer;
use App\Package;

class FooterObserver
{
    public function saving(Footer $footer)
    {
        cache()->delete('cache_footer_'.$footer->id);
    }
    public function deleted(Footer $footer)
    {
        cache()->delete('cache_footer_'.$footer->id);
    }
}
