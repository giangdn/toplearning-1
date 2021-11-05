<?php

namespace App\Http\Middleware;

use App\Permission;
use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ManagerMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (app('auth')->guard($guard)->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        if (Permission::isUnitManager()) {
            return $next($request);
        }
        throw UnauthorizedException::forPermissions([]);
    }
}
