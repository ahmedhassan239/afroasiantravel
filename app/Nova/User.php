<?php

namespace App\Nova;


use ClassicO\NovaMediaLibrary\MediaLibrary;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Silvanite\NovaToolPermissions\Role;

class User extends Resource
{
    use TabsOnEdit;

    public static $model = \App\User::class;


    public static $title = 'name';


    public function authorizedToView(Request $request)
    {
        return false;
    }

    public static $search = [
        'id', 'name', 'email',
    ];
    public static $group = 'User Settings';
    public static $priority =1;


    //uncomment this function to make sid bar icon
    public static function icon()
    {
        return '<img style="width:20px;height:20px; margin-right: 5px; margin-top: 5px"  src="/icons/Users.png" />';
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),
            // MediaLibrary::make('Photo','profile_photo')->types('image'),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()

                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            BelongsToMany::make('Roles', 'roles', Role::class),
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
