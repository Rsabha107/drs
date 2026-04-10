<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventNote extends Model
{
    use HasFactory;

    protected $table = 'event_notes';

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
