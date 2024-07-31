<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Flexible;
use Waynestate\Nova\CKEditor;
// use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Infinety\Filemanager\FilemanagerField;

class Package extends Resource
{

    use TabsOnEdit;
    use TranslatableTabToRowTrait;
    // use HasDependencies;

    


    public static $model = \App\Package::class;


    public static $title = 'name';
    public static $group = 'Destinations';
    public static $priority = 4;
   

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Packages.png" />';
    }


    public static $search = [
        'name','slug','id'
    ];



    public function fields(Request $request)
    {//dd($request->all());

        $destination = $this->destination->slug ?? '' ;
        return [
            ID::make()->sortable()->hideFromIndex(),

            new Tabs('Packages',
                [

                    'Basic Information'=> array(
                        // Number::make('Serial No','serial')->rules('required')->hideFromIndex(),
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
                        Multiselect::make('City', 'city')
                        ->options(\App\City::pluck('name', 'id')->toArray())
                        ->reorderable()
                        ->hideFromIndex(),
                        Multiselect::make('Services', 'category')
                        ->options(\App\Category::pluck('name', 'id')->toArray())
                        ->reorderable()
                        ->hideFromIndex(),                          
                            Boolean::make('Active','status'),
                            // Boolean::make('Featured'),
                            Boolean::make('Popular')->hideFromIndex(),
                            // Boolean::make('Multi Country Package','multi')->hideFromIndex(),
                    ),
                    'Package Details'=>[
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
                        Number::make('Duration in Days','days')->hideFromIndex(),
                        Number::make('From (Starting Price)','start_price')->hideFromIndex(),
                        Number::make('Price After Discount','discount')->hideFromIndex(),
                        Text::make('Location Package Map')->hideFromIndex(),
                    ],
                    'Package Itinerary'=>[
                            Flexible::make('Day Data')
                                ->addLayout('Day', 'wysiwyg', [
                                    Number::make('Day Number')->rules('required')->hideFromIndex(),
                                    Translatable::make('Day Title')->rules('required')->hideFromIndex(),
                                    Translatable::make('Day Summary')->rules('required')->trix()->hideFromIndex(),
                                    Boolean::make('BreakFast')->hideFromIndex(),
                                    Boolean::make('Lunch')->hideFromIndex(),
                                    Boolean::make('Dinner')->hideFromIndex(),
                                ])->confirmRemove()->button('Add Day')
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
            
                    'Related Packages'=>[
                        Multiselect::make('Related Packages')->options(
                            \App\Package::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                        NovaTabTranslatable::make([
                        Textarea::make('Notes')->hideFromIndex()
                        ])
                    ],       
                    'Images' => [
                        new Panel('Images', $this->images()),

                        Flexible::make('Gallery','gallery')
                        ->addLayout('Gallery', 'wysiwyg', [
                            Text::make('Alt'),
                            FilemanagerField::make('Image')->displayAsImage()->hideFromIndex(),

                        ])->confirmRemove()->button('Add Gallery'),
                        
                        Flexible::make('Videos')
                            ->addLayout('Youtube Videos', 'wysiwyg', [
                                Text::make('Youtube Videos'),
                            ])->confirmRemove()->button('Add Youtube Video')
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
