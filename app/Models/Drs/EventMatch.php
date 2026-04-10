<?php

namespace App\Models\Drs;

use App\Models\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMatch extends Model
{
    use HasFactory;
    protected $table = 'matches';

    public function active_status()
    {
        return $this->belongsTo(GlobalStatus::class, 'active_flag');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}
