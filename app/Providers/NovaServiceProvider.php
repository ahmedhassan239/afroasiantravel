<?php

namespace App\Providers;


use App\Nova\Metrics\Blogs;
use App\Nova\Metrics\Excursions;

use App\Nova\Metrics\MythsFacts;

use App\Nova\Metrics\packages;

use ClassicO\NovaMediaLibrary\NovaMediaLibrary;

use Illuminate\Support\Facades\Gate;
use Coroowicaksono\ChartJsIntegration\StackedChart;
use Coroowicaksono\ChartJsIntegration\AreaChart;
use Coroowicaksono\ChartJsIntegration\LineChart;



use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Anaseqal\NovaSidebarIcons\NovaSidebarIcons;

use App\Nova\Metrics\Articles;
use App\Nova\Metrics\Cities;
use App\Nova\Metrics\Countries;
use App\Nova\Metrics\Cruises;
use App\Nova\Metrics\TravelGuids;
use Infinety\Filemanager\FilemanagerTool;
use OptimistDigital\NovaLang\NovaLang;
use Silvanite\NovaToolPermissions\NovaToolPermissions;
use SaintSystems\Nova\ResourceGroupMenu\ResourceGroupMenu;


class NovaServiceProvider extends NovaApplicationServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Spatie\NovaTranslatable\Translatable::defaultLocales(['en','es','pt']);

        parent::boot();
        Nova::sortResourcesBy(function ($resource) {
            return $resource::$priority ?? 99999;
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [


            // new \Tightenco\NovaGoogleAnalytics\PageViewsMetric,
            // new \Tightenco\NovaGoogleAnalytics\VisitorsMetric,
            // new \Tightenco\NovaGoogleAnalytics\OneDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\SevenDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\FourteenDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\TwentyEightDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\SessionsByCountryMetric,
            // new \Tightenco\NovaGoogleAnalytics\SessionDurationMetric,
            // new \Tightenco\NovaGoogleAnalytics\SessionsByDeviceMetric,
            // new \Tightenco\NovaGoogleAnalytics\SessionsMetric,
            // new \Tightenco\NovaGoogleAnalytics\MostVisitedPagesCard,
            // new \Tightenco\NovaGoogleAnalytics\ReferrersList,

        // new Countries,
        // new Cities,
        // new Packages,
        // new Cruises,
        // new Excursions,
        // new Articles,
        // new Blogs,
        // new TravelGuids,
        // new MythsFacts,
        // new TravelGuid,



        //    (new StackedChart())
        //        ->title('Packages')
        //     //    ->model('\App\Package')// Use Your Model Here
        //        ->animations([
        //            'enabled' => true,
        //            'easing' => 'easeinout',
        //        ])
        //        ->series(array([
        //            'barPercentage' => 0.5,
        //            'label' => 'Average Packages',
        //            'backgroundColor' => '#999',
        //            'data' => [80, 90, 80, 40, 62, 79, 79, 90, 90, 90, 92, 91],
        //        ],[
        //            'barPercentage' => 0.5,
        //            'label' => 'Destination Packages',
        //            'backgroundColor' => '#F87900',
        //            'data' => [40, 62, 79, 80, 90, 79, 90, 90, 90, 92, 91, 80],
        //        ]))
        //        ->options([
        //            'xaxis' => [
        //                'categories' => [ 'Jan', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct' ]
        //            ],
        //        ])
        //        ->width('2/3'),
        //        new Articles,
        //        new Blogs,
        //    (new AreaChart())
        //        ->title('Excursions')
        //        ->animations([
        //            'enabled' => true,
        //            'easing' => 'easeinout',
        //        ])
        //        ->series(array([
        //            'barPercentage' => 0.5,
        //            'label' => 'Average Excursions',
        //            'backgroundColor' => '#f7a35c',
        //            'data' => [80, 90, 80, 40, 62, 79, 79, 90, 90, 90, 92, 91],
        //        ],[
        //            'barPercentage' => 0.5,
        //            'label' => 'Destination Excursions',
        //            'backgroundColor' => '#90ed7d',
        //            'data' => [90, 80, 40, 22, 79, 129, 30, 40, 90, 92, 91, 80],
        //        ]))
        //        ->options([
        //            'xaxis' => [
        //                'categories' => [ 'Jan', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct' ]
        //            ],
        //        ])
        //        ->width('2/3'),




        //    (new LineChart())
            //    ->title('Cruises')
            //    ->animations([
            //        'enabled' => true,
            //        'easing' => 'easeinout',
            //    ])
            //    ->series(array([
            //        'barPercentage' => 0.5,
            //        'label' => 'Average Cruises',
            //        'borderColor' => '#f7a35c',
            //        'data' => [80, 90, 80, 40, 62, 79, 79, 90, 90, 90, 92, 91],
            //    ],[
            //        'barPercentage' => 0.5,
            //        'label' => 'Destination Cruises',
            //        'borderColor' => '#90ed7d',
            //        'data' => [90, 80, 40, 22, 79, 129, 30, 40, 90, 92, 91, 80],
            //    ]))
            //    ->options([
            //        'xaxis' => [
            //            'categories' => [ 'Jan', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct' ]
            //        ],
            //    ]) ->width('2/3'),
            //    new TravelGuids,

        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [

        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            // new NovaMediaLibrary(),
            new NovaSidebarIcons,
            new NovaLang,
            new NovaToolPermissions(),
            new ResourceGroupMenu,
            new FilemanagerTool(),
            // new \Spatie\BackupTool\BackupTool(),
            \ChrisWare\NovaBreadcrumbs\NovaBreadcrumbs::make(),
            // new \Energon7\MenuBuilder\MenuBuilder(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function resources()
    {
        Nova::resourcesIn(app_path('Nova'));


    }
}
