<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class OrderTokenService
{
    /**
     * Encode order ID securely into a token (can include expiry).
     */
    public function encode(int $id, ?Carbon $expiresAt = null): string
    {
        $payload = [
            'u_id' => $id,
            'ts' => now()->toDateTimeString(),
            'expires_at' => $expiresAt?->toDateTimeString(),
        ];

        return base64_encode(Crypt::encryptString(json_encode($payload)));
    }

    /**
     * Decode token back to order ID and validate expiry.
     */
    public function decode(string $token): ?int
    {
        try {
            $json = Crypt::decryptString(base64_decode($token));
            $data = json_decode($json, true);

            if (isset($data['expires_at']) && Carbon::now()->gt(Carbon::parse($data['expires_at']))) {
                return null; // Expired
            }

            return $data['u_id'] ?? null;
        } catch (\Throwable $e) {
            return null; // Invalid or tampered token
        }
    }
}
