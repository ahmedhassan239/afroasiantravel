<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;


class Category extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;


    public static $model = \App\Category::class;


    public static $title = 'name';

    public static function label() {
        return 'Services';
    }

    public static $group = 'Destinations';
    public static $priority = 3;

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Categories.png" />';
    }


    public static $search = [
        'id','name','slug'
    ];




    public function fields(Request $request)
    {
        return[
            new Tabs('Services',
                [
                    'Basic Information'=> array(
                        ID::make()->sortable()->hideFromIndex(),
                        NovaTabTranslatable::make([
                            Text::make('Name','name')
                                ->sortable()
                                ->rules('required_lang:en','max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),
                            Textarea::make('Short Description')->hideFromIndex(),
                            CKEditor::make('Description')->hideFromIndex(),
                        ]),

                        Text::make('Icon')->hideFromIndex(),
                        Boolean::make('Active','status')->rules('required'),
                   
                    ),
                    
                    'Images' => [
                        new Panel('Images', $this->images()),
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
        return [
            new DestinationSort,
            new Active
        ];
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
