<?php

namespace Laravel\Nova\Http\Controllers;

use Laravel\Nova\Nova;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\DashboardRequest;

class DashboardController extends Controller
{
    /**
     * Return the details for the Dashboard.
     *
     * @param  \Laravel\Nova\Http\Requests\DashboardCardRequest  $request
     * @param  string  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function index(DashboardRequest $request, $dashboard = 'main')
    {
        $user=Auth::user();
        // if($user->email_verified_at){
            $instance = Nova::dashboardForKey($dashboard, $request);

            abort_if(is_null($instance) && $dashboard !== 'main', 404);
            return response()->json([
                'label' => ! $instance ? __('Dashboard') : $instance->label(),
                'cards' => $request->availableCards($dashboard),
            ]);
        // }
    }
}
