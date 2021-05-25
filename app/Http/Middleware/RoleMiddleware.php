<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->expectsJson()){
                throw UnauthorizedException::notLoggedIn();
            }
            return Redirect::guest( route('login') );
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! Auth::guard($guard)->user()->hasAnyRole($roles)) {
            if ($request->expectsJson()){
                throw UnauthorizedException::forRoles($roles);
            }
            return Redirect::guest( route('home') );
        }

        return $next($request);
    }
}
