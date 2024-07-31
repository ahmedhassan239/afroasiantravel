<?php

namespace App\Observers;

use App\SidePhoto;

class SidePhotoObserver
{
    public function saving(SidePhoto $sidePhoto)
    {
        cache()->delete('cache_side_photo');
    }
    public function deleted(SidePhoto $sidePhoto)
    {
        cache()->delete('cache_side_photo');
    }
}
