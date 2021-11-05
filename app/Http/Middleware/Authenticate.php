<?php

namespace App\Http\Middleware;

use Closure;
use TorMorten\Eventy\Facades\Events;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        Events::action('auth.middleware_handle', $request);

        if (!\Auth::check()) {
            $logout = route('logout');
            if(session()->get('url_previous') == $logout || in_array('redirect', explode('/',session()->get('url_previous'))) ) {
                session()->forget('url_previous');
            }

            if (url_mobile()){
                return redirect()->route('login');
            }
            //return redirect()->route('home_outside',['type' => 0]);
            return redirect()->route('login');
        }

        return $next($request);
    }
}
