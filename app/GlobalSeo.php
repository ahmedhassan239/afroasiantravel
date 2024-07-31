<?php

namespace App;

use App\Observers\GlobalSeoObserver;
use App\Traits\IsTranslatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GlobalSeo extends Model
{
    use IsTranslatable;
    public $table = "global_seo_settings";
    public $translatable =['title', 'keywords', 'robots','description','facebook_description',
                            'facebook_image', 'twitter_title', 'twitter_description', 'twitter_image',
                            'revisit_after', 'canonical_url', 'yahoo_key', 'yandex_verification',
                            'microsoft_validate', 'facebook_page_id', 'author', 'pingback_url',
                            'alexa_code', 'facebook_advert_pixel_tag','google_site_verification',
                            'google_tag_manager_header', 'google_tag_manager_body', 'google_analytics',
                            'live_chat_tag', 'footer_script', 'facebook_site_name', 'facebook_admins',
                            'twitter_site', 'twitter_card','og_type','og_title','og_url','twitter_label1',
                            'twitter_data1'];



    protected static function boot()
    {
        parent::boot();

        static::observe(GlobalSeoObserver::class);
    }



}

