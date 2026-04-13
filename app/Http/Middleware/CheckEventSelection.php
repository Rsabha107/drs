<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckEventSelection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // appLog('CheckEventSelection');
        if (config('mds.check_event_selection')) {
            // appLog('CheckEventSelection: Checking event selection: '. session()->has('EVENT_ID'));
            if (!session()->has('EVENT_ID') && auth()->check()) {
                if (auth()->user()->hasRole('SuperAdmin')) {
                    session()->put('EVENT_ID', getEventIdByLabel('Admin'));
                    // Log::info('CheckEventSelection: event id for amdin is '. getEventIdByLabel('Admin'));
                    // Log::info('CheckEventSelection: SuperAdmin - setting EVENT_ID to Admin');
                    // Log::info('CheckEventSelection: EVENT_ID set to '. session()->get('EVENT_ID'));
                    // Log::info('CheckEventSelection: Redirecting to admin report drs.admin.report');
                    return redirect()->route('drs.admin.drs');
                } elseif (auth()->user()->hasRole('Customer')) {
                    return redirect()->route('drs.customer.report.pick');
                } else {
                    // appLog('CheckEventSelection: Redirecting to pick event');
                    return redirect()->route('login');
                }
            }
        }
        return $next($request);
    }
}
