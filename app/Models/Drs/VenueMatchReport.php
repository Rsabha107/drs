<?php

namespace App\Models\Drs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class VenueMatchReport extends Model
{
    //
    use HasFactory;
    protected $guarded = [];
    protected $table = 'venue_match_reports';

    public $timestamps = false; // <-- Add this

    protected $appends = ['match_date_dmy'];

    public function getMatchDateDmyAttribute()
    {
        return $this->match_date
            ? Carbon::parse($this->match_date)->format('d/m/Y')
            : null;
    }

    public function photos()
    {
        return $this->hasMany(VenueMatchReportDocument::class, 'report_id');
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

    public function vocIssues()
    {
        return $this->hasMany(VocIssue::class, 'report_id');
    }

    public function vocIssuesCritical()
    {
        return $this->hasMany(VocIssue::class, 'report_id')->where('category', 'Issue');
    }

    public function team_a()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function team_b()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function match()
    {
        return $this->belongsTo(EventMatch::class, 'match_number');
    }
}
