<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\Textarea;

class City extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\City::class;


    public static $title = 'name';
    public static $group = 'Destinations';
    public static $priority = 2;


    public static $search = [
        'id','name','slug',
    ];
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/cities.png" />';
    }


    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }


    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->hideFromIndex()->sortable(),
            
            new Tabs('City',
            [
                'Basic Information'=> array(
                    NovaTabTranslatable::make([
                        Text::make('Name','name')
                        ->sortable()
                        ->rules('required_lang:en','max:255'),
                        Slug::make('Slug')
                        ->from('Name')
                        ->sortable()
                        ->rules('required_lang:en', 'max:255'),
                        Trix::make('Description','description'),
                    ]),
                    BelongsTo::make('Destination','destination'),
                ),
                'Images' => [
                    NovaTabTranslatable::make([
                        Text::make('Alt')->hideFromIndex(),
                        Text::make('Thumb Alt')->hideFromIndex(),
                    ]),
                    FilemanagerField::make('Banner','_banner')->displayAsImage()->hideFromIndex(),
                    FilemanagerField::make('Thumb','_thumb')->displayAsImage()->hideFromIndex(),
                ],
                'Seo' => [
                    new Panel('Seo', $this->seo()),
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
