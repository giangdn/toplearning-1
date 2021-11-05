<?php

namespace App\Http\Middleware;

use App\Permission;
use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if (app('auth')->guard($guard)->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (Permission::isUnitManager()){
                if (in_array($permission,['dashboard','plan-suggest','plan-app','user','approve-register']))
                    return $next($request);
            }
            if(\Auth::user()->isTeacher()){
                if (in_array($permission,['virtual-classroom','quiz-grading']))
                    return $next($request);
            }
            if (app('auth')->guard($guard)->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
