<?php

namespace App\Models\Drs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class WorkforceDailyReport extends Model
{
    //
    use HasFactory;
    protected $guarded = [];
    protected $table = 'workforce_daily_reports';

    public $timestamps = false; // <-- Add this

    public function photos()
    {
        return $this->hasMany(WorkforceDailyReportDocument::class, 'report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dayType()
    {
        return $this->belongsTo(DayType::class, 'day_type_id');
    }
}
