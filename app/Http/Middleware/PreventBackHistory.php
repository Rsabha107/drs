<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // return $response;
        // return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
        //                 ->header('Pragma','no-cache')
        //                 ->header('Expires','Sat, 26 Jul 1997 05:00:00 GMT');

        // $response = $next($request);
        // $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        // $response->headers->set('Pragma', 'no-cache');
        // $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        // return $response;

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'master-only');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');

        return $response;
    }
}
