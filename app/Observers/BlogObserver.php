<?php

namespace App\Observers;

use App\Blog;

class BlogObserver
{
    public function saving(Blog $blog)
    {
        cache()->delete('cache_blog_'.$blog->id);
        cache()->delete('cache_blog_featured_'.$blog->destinations->id);
    }

    public function deleted(Blog $blog)
    {
        cache()->delete('cache_blog_'.$blog->id);
        cache()->delete('cache_blog_featured_'.$blog->id);
    }
}
