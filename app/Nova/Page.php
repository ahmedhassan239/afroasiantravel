<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use Bessamu\AjaxMultiselectNovaField\AjaxMultiselect;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Page extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;

    public static $model = \App\Page::class;


    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Pages.png" />';
    }

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public static $title = 'name';
    public static $group = 'Content';
    public static $priority = 1;
    public static function label() {
        return 'Templates';
    }
    public static $search = [
        'id','name','slug'
    ];


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;

        return [
            ID::make()->sortable()->hideFromIndex(),

            new Tabs('Pages',
                [
                    'Basic Information'=> array(
                        NovaTabTranslatable::make([
                            Text::make('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),
                            Text::make('Page Title')
                                ->rules('required_lang:en', 'max:255')->hideFromIndex(),

                            CKEditor::make('Description')->rules('required_lang:en')->hideFromIndex(),
                        ]),
                        BelongsTo::make('Destination')->rules('required'),
                        Boolean::make('Active','status'),
                    ),

                    'Images' => [
                        new Panel('Images', $this->images()),
                    ],
                    'Gallery' => [
                        Flexible::make('New Gallery','_gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Gallery')->displayAsImage()->folder($destination)->hideFromIndex(),

                        ])->confirmRemove()->button('Add Gallery')

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
