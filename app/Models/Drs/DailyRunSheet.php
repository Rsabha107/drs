<?php

namespace App\Models\Drs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyRunSheet extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'daily_run_sheets';

    protected $appends = ['run_date_dmy'];

    public function getRunDateDmyAttribute()
    {
        return $this->run_date
            ? Carbon::parse($this->run_date)->format('d/m/Y')
            : null;
    }

    public function items()
    {
        return $this->hasMany(DailyRunSheetItem::class, 'run_sheet_id')->orderBy('sort_order');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function match()
    {
        return $this->belongsTo(EventMatch::class, 'match_id');
    }

    public function functionalArea()
    {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id');
    }

    public function sheetType()
    {
        return $this->belongsTo(SheetType::class, 'sheet_type_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
