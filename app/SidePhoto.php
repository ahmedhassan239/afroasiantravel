<?php

namespace App;

use App\Observers\SidePhotoObserver;
use ClassicO\NovaMediaLibrary\API;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class SidePhoto extends Model implements Sortable
{
    public $table = "side_photos";
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::observe(SidePhotoObserver::class);
    // }
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];


    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
}
