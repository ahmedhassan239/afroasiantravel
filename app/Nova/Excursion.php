<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\VaporFile;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Excursion extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;


    public static $model = \App\Excursion::class;


    public static $title = 'name';

    public static $group = 'Destinations';
    public static $priority = 5;

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px" src="/icons/Excursions.png" />';
    }

    public static $search = [
        'id','slug','name'
    ];


    public function fields(Request $request)
    {
        $destination = $this->destination->slug ?? '' ;

        return [
            ID::make()->sortable()->hideFromIndex(),
            new Tabs('Excursions',
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

                            Textarea::make('Description" Max 300 Characters "','description')->rules('required_lang:en','max:300')->hideFromIndex(),
                            CKEditor::make('Overview')->hideFromIndex(),

                        ]),
                        BelongsTo::make('Destination','destination'),
                        AjaxSelect::make('City','city_id')
                            ->get('/api/city/{destination}')
                            ->parent('destination')->rules('required'),
                
                        Boolean::make('Active','status'),
                        Boolean::make('Popular','hot_offer')->hideFromIndex(),
                    ),
                    'Excursion Details'=>[
                        Select::make('Rate')->options([
                            '1' => '1 Star',
                            '2' => '2 Stars',
                            '3' => '3 Stars',
                            '4' => '4 Stars',
                            '5' => '5 Stars',
                        ])->hideFromIndex(),
                        Multiselect::make('Languages')->options([
                            'English'=>'English',
                            'Spanish'=>'Spanish',
                            'Italian'=>'Italian',
                            'Portuguese'=>'Portuguese',
                            'German'=>'German',
                            'Russian'=>'Russian',
                            'Chinese'=>'Chinese',
                            'Japanese'=>'Japanese',
                        ])->reorderable()->hideFromIndex(),
                        Multiselect::make('Transportation')->options([
                            'Bus'=>'Bus',
                            'Limousine'=>'Limousine',
                            'Airplane'=>'Airplane',
                        ])->reorderable()->hideFromIndex(),
                        Number::make('Duration')->hideFromIndex(),
                        Number::make('From (Starting Price)','start_price')->hideFromIndex(),
                        Number::make('Price After Discount','discount')->hideFromIndex()
                    ],
                    
                    'Including'=>[
                        Heading::make('Including'),
                            Multiselect::make('Included')->options(
                            \App\Option::where('type','include')->pluck('content','id')
                        )->reorderable()->hideFromIndex(),
                        Heading::make('Excluding'),
                        Multiselect::make('Excluded')->options(
                            \App\Option::where('type','exclude')->pluck('content','id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Related Excursions' => [
                        Heading::make('Choose Related Excursions'),
                        Multiselect::make('Related Excursions')->options(
                            \App\Excursion::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Location' => [
                        Text::make('Arrive Location')->hideFromIndex(),
                        Text::make('Departure Location')->hideFromIndex(),
                        Text::make('Location','location_description')->hideFromIndex(),
                        Text::make('Location URL For Google Map','map_url')->hideFromIndex(),
                    ],
                    'Images' => [

                        new Panel('Images', $this->images()),

                        Flexible::make('Gallery','gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Image')->displayAsImage()->hideFromIndex(),

                        ])->confirmRemove()->button('Add Gallery'),

                    ],
                    
                    'Icons' =>[
                        Boolean::make('Accessibility')->hideFromIndex(),
                        Boolean::make('Location','location_icon')->hideFromIndex(),
                        Boolean::make('Tour Guide')->hideFromIndex(),
                        Boolean::make('Schedule')->hideFromIndex(),
                    ],
                 
                    'Seo' => [
                        new Panel('Seo', $this->seo()),
                    ],
                ])
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
