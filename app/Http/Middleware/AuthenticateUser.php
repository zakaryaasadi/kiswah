<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;


class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        if ($request->is('api/*') ) {
            $request->headers->set('Accept', 'application/json');
        }
        if (Auth::guard('users')->check()) {
            if ($request->user()->tokenCan('view_as_admin')) {
                return $next($request);
            }
            if ($request->expectsJson()) {
                return response()->json(['status' => 'Authorization Token does not have permissions'], 401);
            }
            if (!$request->expectsJson()) {
                return route('login');
            }
        } else {

            if ($request->expectsJson()) {
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
            if (!$request->expectsJson()) {
                return route('login');
            }
        }

    }


}
