<?php
namespace App\Nova\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait RedirectAfterAction{

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return static::redirectAfterAction();
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return static::redirectAfterAction();
    }

    public static function redirectAfterDelete(NovaRequest $request)
    {
        return static::redirectAfterAction();
    }

    public static function redirectAfterAction()
    {
        return '/resources/'.static::uriKey();
    }
}
