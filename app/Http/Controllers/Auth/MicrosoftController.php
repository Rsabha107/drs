<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftController extends Controller
{
    public function redirectToMicrosoft(Request $request)
    {
        // Redirect the user to the Microsoft login page
        return Socialite::driver('microsoft')->redirect();
    }

    public function handleMicrosoftCallback()
    {
        try {

            $azureUser = Socialite::driver('microsoft')->user();
            $finduser = User::where('email', $azureUser->email)->first();

            if (!$finduser) {

                appLog('Logging in existing user from Microsoft: ' . $azureUser->email);
                // Auth::login($finduser);
                // Auth::guard('web')->logout();
                // $request->session()->invalidate();
                // $request->session()->regenerateToken();
                // $tenantId = config('services.microsoft.tenant_id'); // from .env
                // $redirectUri = urlencode(route('home')); // or any route you want after logout
                $microsoftLogoutUrl = Socialite::driver('microsoft')->getLogoutUrl(route('login')); // Replace 'azure' with your Microsoft Socialite driver name if different, and 'login' with your desired redirect URI after Microsoft logout.
                return redirect($microsoftLogoutUrl)->with('error', 'Your Microsoft account is not authorized to access VMS. Please contact the administrator for assistance.');
                // return redirect('login')->with('error', 'Your Microsoft account is not authorized to access VAPP. Please contact the administrator for assistance.');
                // return redirect()->intended('/');
            }
            appLog('Creating new user from Microsoft login: ' . $azureUser->email);

            $finduser->update([
                'socialite_id' => $azureUser->getId(),
                'socialite_token' => $azureUser->token,
                'name' => $azureUser->getName(),
                'provider' => 'microsoft',
                'provider_id' => $azureUser->getId(),
            ]);

            // $newUser = User::updateOrCreate(
            //     ['email' => $azureUser->email],
            //     [
            //         'name' => $azureUser->name,
            //         'socialite_id' => $azureUser->getId(),
            //         'socialite_token' => $azureUser->token,
            //         // 'password' => encrypt('123456dummy'),
            //         'phone' => $socialiteUser->mobilePhone ?? '0000000000',
            //         'usertype' => 'user',
            //         'is_admin' => 0,
            //         'role' => 'user',
            //         'status' => 1,
            //         'employee_id' => 0,
            //     ]
            // );

            // $user = User::updateOrCreate(
            //     ['email' => $azureUser->getEmail()],
            //     [
            //         'name' => $azureUser->getName(),
            //         'provider' => 'microsoft',
            //         'provider_id' => $azureUser->getId(),
            //         'socialite_id' => $azureUser->getId(),
            //         'socialite_token' => $azureUser->token,
            //     ]
            // );

            appLog('Logging in new user from Microsoft: ' . $finduser->email);
            Auth::login($finduser);

            session(['login_method' => 'microsoft']);

            appLog('Logged in new user from Microsoft: ' . $finduser->email);
            return redirect()->intended('/');
        } catch (Exception $e) {
            // dd($e->getMessage());
            appLog('exception handleMicrosoftCallback: ' . $e->getMessage());
            Auth::guard('web')->logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            // $tenantId = config('services.microsoft.tenant_id'); // from .env
            // $redirectUri = urlencode(route('home')); // or any route you want after logout
            $microsoftLogoutUrl = Socialite::driver('microsoft')->getLogoutUrl(route('login')); // Replace 'azure' with your Microsoft Socialite driver name if different, and 'login' with your desired redirect URI after Microsoft logout.
            return redirect($microsoftLogoutUrl);
            return redirect('login')->with('error', 'Unable to login using Microsoft. Please try again.');
        }
    }
}
