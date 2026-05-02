<?php
// app/Services/SignedUserLinkGenerator.php
namespace App\Services;

use App\Models\SignedUrlToken;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SignedUserLinkGenerator
{
    public static function generate(string $name, string $email, string $event_id, int $functional_area_id = null, int $venue_id = null, int $validMinutes = 30): string
    {
        // Generate a unique token
        $token = Str::random(64);
        $expiresAt = now()->addMinutes($validMinutes);

        // Store the token in the database
        SignedUrlToken::create([
            'token' => $token,
            'email' => $email,
            'used' => false,
            'expires_at' => $expiresAt,
        ]);

        $verifyUrl = URL::temporarySignedRoute(
            'auth.signup',
            $expiresAt,
            [
                'name' => $name,
                'email' => $email,
                'event_id' => $event_id,
                'functional_area_id' => $functional_area_id,
                'venue_id' => $venue_id,
                'token' => $token, // Add token to URL
            ],
        );
        
        Log::info("Generated signed link for user creation: {$verifyUrl}");
        Log::info("Site Url: " . config('app.url'));

        return $verifyUrl;
    }
}
