<?php

namespace App\Nova;

use App\Nova\Filters\Active;
use App\Nova\Filters\DestinationSort;
use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Froala\NovaFroalaField\Froala;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Monaye\SimpleLinkButton\SimpleLinkButton;
use OptimistDigital\MultiselectField\Multiselect;
use Waynestate\Nova\CKEditor;

class Blog extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;


    public static $model = \App\Blog::class;

    // public function authorizedToView(Request $request)
    // {
    //     return false;
    // }


    public static $title = 'name';
    public static $group = 'Content';
    public static $priority = 3;


    public static $search = [
        'id','name','slug'
    ];

    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Blogs.png" />';
    }


    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            new Tabs('Blogs',
                [
                    'Basic Information'=> array(
                        NovaTabTranslatable::make([
                            Text::make('Name','name')
                                ->sortable()
                                ->rules('required_lang:en', 'max:255'),
                            Slug::make('Slug')
                                ->from('Name')
                                ->sortable()
                                ->rules('required_lang:en','max:255')->hideFromIndex(),
                            Textarea::make('Description" Max 150 Characters "','description')->rules('required_lang:en','max:150')->hideFromIndex(),
                            CKEditor::make('Overview')->rules('required_lang:en')->hideFromIndex(),
//                            MediaLibrary::make('For Trix')
//                                ->trix('Content'),
//                            Trix::make('Content')
//                                ->withMeta([ 'extraAttributes' => [ 'nml-trix' => 'Content' ] ])
//                              Froala::make('Content')->withFiles('nova/nova-media-library'),
                        ]),
                        BelongsTo::make('Destination','destination'),
//                        BelongsTo::make('Category','categories'),
                        Boolean::make('Active','status'),
                        // Boolean::make('Featured'),
                    ),
                    'Related Blogs' => [
                  
                        Heading::make('Choose Related Blogs'),
                        Multiselect::make('Related Blogs')->options(
                            \App\Blog::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),
                    ],
                    'Related Packages' => [
                        Heading::make('Choose Related Packages'),
                        Multiselect::make('Related Packages')->options(
                            \App\Package::where('destination_id',$this->destination_id)->where('status',1)->pluck('name', 'id')
                        )->reorderable()->hideFromIndex(),                        
                    ],

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
