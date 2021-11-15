<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Tracking;
use Illuminate\Support\Facades\Log;

class RenderMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (config('app.debug', false)) {
            $scopes = ['db', 'app', 'lay'];

            foreach ($scopes as $scope) {
                Tracking::dump($scope);
                Tracking::dumpSlow($scope);
            }
        }

        return $response;
    }
}
