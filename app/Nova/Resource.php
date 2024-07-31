<?php

namespace App\Nova;
use App\Nova\Traits\RedirectAfterAction;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

use Laravel\Nova\Http\Requests\NovaRequest;
use SaintSystems\Nova\ResourceGroupMenu\DisplaysInResourceGroupMenu;
use Laravel\Nova\Resource as NovaResource;
use Waynestate\Nova\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;
use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Infinety\Filemanager\FilemanagerField;
// use Epartment\NovaDependencyContainer\HasDependencies;

abstract class Resource extends NovaResource
{
    use Breadcrumbs;
    use RedirectAfterAction;
    use DisplaysInResourceGroupMenu;
    
    // use HasDependencies;
    

    public function seo()
    {
        $destination = $this->destination->slug ?? '' ;
        return[
            NovaTabTranslatable::make([
                Text::make('Page Title','seo_title')->hideFromIndex(),
                Text::make('Meta Keywords','seo_keywords')->hideFromIndex(),
                Text::make('Robots','seo_robots')->hideFromIndex(),
                Textarea::make('Meta Description','seo_description')->hideFromIndex(),
                Text::make('Facebook Title','og_title')->hideFromIndex(),
                Textarea::make('Facebook Description')->hideFromIndex(),
                Text::make('Twitter Title')->hideFromIndex(),
                Textarea::make('Twitter Description')->hideFromIndex(),
            ]),
          
            FilemanagerField::make('Facebook Image','_facebook_image')->displayAsImage()->folder($destination)->hideFromIndex(),
            FilemanagerField::make('Twitter Image','_twitter_image')->displayAsImage()->folder($destination)->hideFromIndex(),
        ];
    }

    public function images(){
        $destination = $this->destination->slug ?? '' ;
        return [
            NovaTabTranslatable::make([
                Text::make('Banner Alt','alt')->rules('required_lang:en','max:255')->hideFromIndex(),
                Text::make('Thumb Alt')->rules('required_lang:en','max:255')->hideFromIndex(),
            ]),
            FilemanagerField::make('Banner','_banner')->displayAsImage()->folder($destination)->hideFromIndex(),
            FilemanagerField::make('Thumb','_thumb')->displayAsImage()->folder($destination)->hideFromIndex(),           
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }


    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }


    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }


    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }
}
