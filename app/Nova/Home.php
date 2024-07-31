<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use MrMonat\Translatable\Translatable;
use OptimistDigital\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Flexible;

class Home extends Resource
{
    use TabsOnEdit;
    use TranslatableTabToRowTrait;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Home::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $group = 'Global Settings';

    public static $title = 'video_title_one';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'video_title_one',
    ];
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/settings.png" />';
    }
    public static function label()
    {
        return 'Home Content';
    }
        public static function authorizedToCreate(Request $request)
    {
        return false;
    }
      public function authorizedToView(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            new Tabs('Home',
            [
            'Video Details'=> array(
                NovaTabTranslatable::make([
                    Text::make('1st Video Title' ,'video_title_one'),
                    Text::make('2nd Video Title' ,'video_title_two')->hideFromIndex(),
                    Textarea::make('Video Description','video_description')->hideFromIndex(),
                    
                ]),
                FilemanagerField::make('Video')->hideFromIndex(),
            ),
            'Services & Trending'=>[
                Multiselect::make('Services', 'category')
                        ->options(\App\Category::pluck('name', 'id')->toArray())
                        ->reorderable()
                        ->hideFromIndex(),

                Multiselect::make('Packages')
                ->options(\App\Package::pluck('name', 'id')->toArray())
                ->reorderable()
                ->hideFromIndex(),
                Multiselect::make('Excursions')
                ->options(\App\Excursion::pluck('name', 'id')->toArray())
                ->reorderable()
                ->hideFromIndex(),

                Multiselect::make('Blogs')
                ->options(\App\Blog::pluck('name', 'id')->toArray())
                ->reorderable()
                ->hideFromIndex(),
            ],     
            'About Us & Tailor-Made'=>[
                NovaTabTranslatable::make([
                    Text::make('About Us Title','about_title'),
                    Textarea::make('About Us Description','about_description')->hideFromIndex(),
                    Text::make('Tailor-Made Title','tailor_title'),
                    Textarea::make('Tailor-Made Description','tailor_description')->hideFromIndex(),
                ])
            ],  
            'Why Choose Us'=>[
                NovaTabTranslatable::make([
                    Text::make('Why Choose Us Title','why_title'),
                    Textarea::make('Why Choose Us Description','why_description')->hideFromIndex(),
                ]),
                Flexible::make('Features','features')
                ->addLayout('feature', 'wysiwyg', [
                    Translatable::make('Title'),
                    Translatable::make('Description'),
                    Text::make('Class'),
                ])->confirmRemove()->button('Add Feature'),
            ],  
            'Our Partners'=>[
                Flexible::make('Partners','partners')
                ->addLayout('partners', 'wysiwyg', [
                    Text::make('Alt'),
                    Text::make('Link'),
                    FilemanagerField::make('Image')->displayAsImage()->hideFromIndex(),
                ])->confirmRemove()->button('Add Partner'),
            ],
            ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
