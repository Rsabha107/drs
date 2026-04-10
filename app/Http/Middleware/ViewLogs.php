<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewLogs
{
    public function handle(Request $request, Closure $next)
    {
        appLog('Checking view logs permission for user: ' . (Auth::check() ? Auth::user()->email : 'Guest'));
        appLog('User roles: ' . (Auth::check() ? implode(', ', Auth::user()->getRoleNames()->toArray()) : 'No roles'));
        if (Auth::check() && Auth::user()->hasRole('SecurityRole')) {
            return $next($request);
        }

        abort(401, 'Unauthorised');
    }
}
