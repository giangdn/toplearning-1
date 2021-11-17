<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Tracking;

class RenderMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (config('app.debug', false)) {
            $dumpScopes = ['db', 'app', 'lay', 'event', 'backtrace'];
            $dumpSlowScopes = ['db', 'app', 'lay', 'event'];

            foreach ($dumpScopes as $scope) {
                Tracking::dump($scope, !in_array($scope, $dumpSlowScopes));
                in_array($scope, $dumpSlowScopes) && Tracking::dumpSlow($scope);
            }
        }

        return $response;
    }
}
