<?php

namespace App\Observers;

use App\Page;

class PageObserver
{
    public function saving(Page $page)
    {

//        cache()->delete('cache_category_'.$page->category->id);
//        cache()->delete('cache_category_'.$page->category->slug);

    }

    public function deleted(Page $page)
    {
//        cache()->delete('cache_category_'.$page->category->id);
//        cache()->delete('cache_category_'.$page->category->slug);
    }

}
