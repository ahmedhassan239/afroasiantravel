<?php

namespace App\Observers;

use App\City;

class CityObserver
{
    /**
     * Handle the City "created" event.
     *
     * @param  \App\City  $city
     * @return void
     */

    public function saving(City $city)
    {
        cache()->delete('cache_city_'.$city->id);
        cache()->delete('cache_city_featured_'.$city->id);
        cache()->delete('cache_city_ex_'.$city->id);
    }
    public function deleted(City $city)
    {
        cache()->delete('cache_city_'.$city->id);
        cache()->delete('cache_city_featured_'.$city->id);
        cache()->delete('cache_city_ex_'.$city->id);
    }

}
