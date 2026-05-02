<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignedUrlToken extends Model
{
    protected $fillable = [
        'token',
        'email',
        'used',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the token is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        if ($this->used) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Mark the token as used
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
        ]);
    }
}
