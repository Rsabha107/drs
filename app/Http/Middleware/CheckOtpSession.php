<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckOtpSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // appLog('CheckOtpSession:: handle: ' . session()->has('OTPSESSIONKEY'));
        // appLog('CheckOtpSession:: logged in: ' . auth()->check());
        // dd(session()->has('OTPSESSIONKEY'));
        // if (true) {
        try {
            if (config('settings.otp_enabled') && auth()->user()->provider === 'local') {
                if (!session()->get('OTPSESSIONKEY') && auth()->check()) {
                    // appLog('CheckOtpSession:: redirect to otp');
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login');
                }
            }
        } catch (\Exception $e) {
            // appLog('CheckOtpSession:: handle exception: ' . $e->getMessage());
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }
        // appLog('CheckOtpSession:: passed');
        // appLog('CheckOtpSession:: user: ' . auth()->user()?->email);
        return $next($request);
    }
}
