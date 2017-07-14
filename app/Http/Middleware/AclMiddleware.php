<?php

namespace App\Http\Middleware;

use Closure;
use Gate;
use Illuminate\Support\Facades\Auth;


class AclMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $acl)
    {
        /**
         * Module Permission
         */
//        if (($acl == '/') and (Gate::denies('/'))) {
//            $url = Auth::user()->modules()->first()->url;
//            return redirect($url);
//        }
//
//        elseif (Gate::denies($acl)) {
//            abort(403);
//        }

        return $next($request);
    }
}
