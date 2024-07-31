<?php

namespace App;

use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Policy extends Model
{
    public $table = "policies";
//    use HasTranslations;
     use IsTranslatable;
    use HasFlexible;
    public $translatable = [
        'price_policy',
        'payment_policy', 'repeated_travellers', 'travel_schedule'
    ];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }//END OF destination
}
