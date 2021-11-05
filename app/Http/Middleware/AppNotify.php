<?php

namespace App\Http\Middleware;

use App\Helpers\AppApi;
use Closure;

class AppNotify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('DeviceModel') && \Auth::check()) {
            $DeviceModel = session()->get('DeviceModel');
            $VersionCode = session()->get('VersionCode');
            $DeviceToken = session()->get('DeviceToken');

            //$api = new AppApi(\Auth::id());
            //$response = $api->register($DeviceModel, $VersionCode, $DeviceToken);
        }

        return $next($request);
    }
}
