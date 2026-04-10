<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UseOriginalHost
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If Azure (or another proxy) sent X-Original-Host,
        // rewrite it as X-Forwarded-Host so Laravel will use it.
        appLog('Checking for X-Original-Host header');
        if ($request->headers->has('X-Original-Host')) {
            appLog('Rewriting X-Forwarded-Host to ');
            $request->headers->set('X-Forwarded-Host', $request->headers->get('X-Original-Host'));
        }

        return $next($request);
    }
}
