<?php
// app/Services/SignedUserLinkGenerator.php
namespace App\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SignedUserLinkGenerator
{
    public static function generate(string $name, string $email, string $event_id, int $validMinutes = 30): string
    {
        $expiresAt = Carbon::now('Asia/Qatar')->addMinutes(30);
        $verifyUrl = URL::temporarySignedRoute(
            // 'user.create.signed',
            'auth.signup', // Ensure this matches the route name in web.php
            // $expiresAt,
            now()->addMinutes($validMinutes),
            ['name' => $name, 'email' => $email, 'event_id' => $event_id],
        );
        appLog("Generated signed link for user creation: {$verifyUrl}");
        appLog("Site Url: " . config('app.url'));

        return $verifyUrl;
        // return $verifyUrl;
    }

}
