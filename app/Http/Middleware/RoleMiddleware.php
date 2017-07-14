<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $role = null)
    {
        if (Auth::guest()) {
            return redirect($urlOfYourLoginPage);
        }

        if (isset($role)) {
            if (!$request->user()->hasRole($role)) {
                abort(403);
            }
        }

        if ((!$request->user()->hasPermission($permission)) AND (!$request->user()->hasRole('Admin'))) {
            abort(403);
        }

        return $next($request);
    }
}
