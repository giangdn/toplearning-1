<?php

namespace Modules\Quiz\Http\Middleware;

use Closure;
use TorMorten\Eventy\Facades\Events;

class Secondary
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
        if (! \Auth::guard('secondary')->check() && !\Auth::check()) {
            $logout = route('logout');
            if(session()->get('url_previous') == $logout) {
                session()->forget('url_previous');
            }
            return redirect()->route('home_outside',['type' => 0]);
        }

        return $next($request);
    }
}
