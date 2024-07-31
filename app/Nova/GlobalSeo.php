<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class GlobalSeo extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\GlobalSeo::class;


    public static $title = 'id';


    public static $group = 'Global Settings';
    public static $priority = 2;

    public static function label()
    {
        return 'Global Seo';
    }



    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    public function authorizedToView(Request $request)
    {
        return false;
    }
    public static $search = [
        'id',
    ];
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px" src="/icons/settings.png" />';
    }


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),

            new Tabs('Global Seo Settings',
            [
                'Meta Tags'=> array(
                    NovaTabTranslatable::make([
                        Text::make('Author')->hideFromIndex(),
                        Text::make('Revisit After')->hideFromIndex(),
                        Text::make('Canonical Url')->hideFromIndex(),
                        Text::make('Page Title','title'),
                        Text::make('Meta Keywords','keywords')->hideFromIndex(),
                        Text::make('Robots','robots')->hideFromIndex(),
                        Textarea::make('Meta Description','description')->hideFromIndex(),
                    ])
                ),

                'Facebook'=>[
                    NovaTabTranslatable::make([
                        Text::make('Facebook Site Name'),
                        Text::make('Facebook Admins')->hideFromIndex(),
                        Text::make('Facebook Page Id')->hideFromIndex(),
                        Text::make('og:type','og_type')->hideFromIndex(),
                        Text::make('og:title','og_title')->hideFromIndex(),
                        Text::make('og:url','og_url')->hideFromIndex(),
                        Textarea::make('Facebook Description')->hideFromIndex(),
                        Code::make('Facebook Advert Pixel Tag (Used Within "head" tag)','facebook_advert_pixel_tag')->language('javascript')->hideFromIndex(),
                    ]),
                        Text::make('Facebook Image')->hideFromIndex(),
                ],

                'Twitter'=>[
                    NovaTabTranslatable::make([
                        Text::make('Twitter Site'),
                        Text::make('Twitter Title')->hideFromIndex(),
                        Text::make('Twitter Card')->hideFromIndex(),
                        Text::make('Twitter Label','twitter_label1')->hideFromIndex(),
                        Text::make('Twitter Data','twitter_data1')->hideFromIndex(),
                        Textarea::make('Twitter Description')->hideFromIndex(),
                    ]),

                        Text::make('Twitter Image')->hideFromIndex(),
                ],


                'Google'=>[
                    NovaTabTranslatable::make([
                        Text::make('Google Site Verification')->hideFromIndex(),
                        Code::make('Google Tag Manager (Used Within "Header" tag)','google_tag_manager_header')->language('javascript')->hideFromIndex(),
                        Code::make('Google Tag Manager (Used Within "Body" tag))','google_tag_manager_body')->language('javascript')->hideFromIndex(),
                        Code::make('Google analytics - Website tracking (Used Within "Header" tag)','google_analytics')->language('javascript')->hideFromIndex(),
                    ])
                ],

                'Global Keys '=>[
                    NovaTabTranslatable::make([
                        Text::make('Yahoo Key')->hideFromIndex(),
                        Text::make('Yandex Verification')->hideFromIndex(),
                        Text::make('Microsoft Validate')->hideFromIndex(),
                        Text::make('Pingback Url')->hideFromIndex(),
                    ])
                ],

                'Global Codes '=>[
                    NovaTabTranslatable::make([
                        Code::make('Third Party (Used Within "head" tag)','alexa_code')->language('javascript')->hideFromIndex(),
                        Code::make('Live chat tag (Used Within "Body" tag)','live_chat_tag')->language('javascript')->hideFromIndex(),
                        Code::make('Footer script (Used Within "Body" tag)','footer_script')->language('javascript')->hideFromIndex(),
                    ])
                ],

            ]),
        ];
    }


    public function cards(Request $request)
    {
        return [];
    }


    public function filters(Request $request)
    {
        return [];
    }


    public function lenses(Request $request)
    {
        return [];
    }


    public function actions(Request $request)
    {
        return [];
    }
}
