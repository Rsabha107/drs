<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OtpMail;
use TechEd\SimplOtp\SimplOtp;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EmailOtpVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.sign-in');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    // : RedirectResponse
    {
        // appLog($request);

        session()->forget('OTPSESSIONKEY');

        $request->authenticate();

        // $user = User::find(Auth::user()->id);
        $user = auth()->user();

        $request->session()->regenerate();

        Auth::logoutOtherDevices($request->password);


        if (config('settings.otp_enabled') && session('login_method') === 'local') {
            // $this->showOtp();
            // $simpleOTP = new SimpleOTP();
            // $code = $simpleOTP->create(auth()->user()->email);

            $key = Str::lower($user->id);

            // Allow 3 attempts every 5 minutes
            if (RateLimiter::tooManyAttempts($key, 3)) {
                $seconds = RateLimiter::availableIn($key);
                                $minutes = floor($seconds / 60);
                $remainingSeconds = $seconds % 60;

                $timeMessage = $minutes > 0
                    ? "{$minutes} minute(s) and {$remainingSeconds} second(s)"
                    : "{$remainingSeconds} second(s)";

                $notification = array(
                    'message' => 'Too many OTP requests. Try again in ' . $timeMessage ,
                    'alert-type' => 'danger'
                );
                return redirect('/auth/otp')->with($notification);

                return response()->json([
                    'message' => 'Too many OTP requests. Try again in ' . $timeMessage
                ], 429);
            }

            // Hit the rate limiter
            RateLimiter::hit($key, 300); // 300 seconds = 5 minutes

            $otp = SimplOtp::generate($user->email);
            if ($otp->status === true) {
                try {
                    $details = [
                        'otp_token' => $otp->token,
                        'body' => 'Your One-Time Password (OTP) is: ' . $otp->token,
                    ];
                    Mail::to($user->email)->send(new OtpMail($details));
                    // $user->notify(new EmailOtpVerification($details));
                } catch (\Exception $e) {
                    appLog('Error sending OTP email: ' . $e->getMessage());
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->with('error', 'Failed to send OTP email. Please try again.');
                }
            } else {
                appLog('Error generating OTP: ' . $otp->message);
            }
            // return view('mds/auth/otp', ['email' => $user->email]);
            return redirect()->route('otp.get');
        }

        // //set the default workspace as set during user creation
        // session()->put('workspace_id', $request->user()->workspace_id);

        // appLog($request->authenticate());
        // appLog($request->user()->role);
        $url = '';
        if ($request->user()->is_admin) {
            $url = 'drs/report';
            return redirect()->intended($url);
        } else {
            $url = 'drs/report';
            return redirect()->intended($url);
        }

        // if ($request->user()->role === 'admin'){
        //     $url = 'mds/admin/booking';
        //     return redirect()->intended($url);
        // } elseif  ($request->user()->role === 'user'){
        //     $url = 'mds/customer/booking';
        //     return redirect()->intended($url);
        // }

        // return back()->withErrors([
        //     'email' => 'Username and password don\'t match.',
        // ])->onlyInput('email');


        return redirect()->intended($url);
        // return redirect()->intended(RouteServiceProvider::HOME);.
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $provider = $request->user()->provider;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($provider === 'local') {
            appLog('Local login - redirecting to login page');
            session()->forget('login_method');
            return redirect('/');
        }

        session()->forget('login_method');
        $microsoftLogoutUrl = Socialite::driver('microsoft')->getLogoutUrl(route('login')); // Replace 'azure' with your Microsoft Socialite driver name if different, and 'login' with your desired redirect URI after Microsoft logout.
        return redirect($microsoftLogoutUrl);

        // return redirect('/');
    }
}
