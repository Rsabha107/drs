<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SheetType extends Model
{
    protected $table = 'sheet_types';
    protected $guarded = [];

    /**
     * Get the event this sheet type belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the venue this sheet type belongs to
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the match this sheet type belongs to
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(EventMatch::class, 'match_id');
    }

    /**
     * Get sheet types available to customers
     */
    public static function forCustomer($eventId = null, $venueId = null)
    {
        $query = self::where('available_to_customer', true)
            ->with('match');

        if ($eventId) {
            $query->where(function ($q) use ($eventId) {
                $q->whereNull('event_id')
                  ->orWhere('event_id', $eventId);
            });
        }

        if ($venueId) {
            $query->where(function ($q) use ($venueId) {
                $q->whereNull('venue_id')
                  ->orWhere('venue_id', $venueId);
            });
        }

        return $query->orderBy('sort_order')->get();
    }

    /**
     * Get all sheet types available to admins
     */
    public static function forAdmin($eventId = null, $venueId = null)
    {
        $query = self::query()
            ->with('match');

        if ($eventId) {
            $query->where(function ($q) use ($eventId) {
                $q->whereNull('event_id')
                  ->orWhere('event_id', $eventId);
            });
        }

        if ($venueId) {
            $query->where(function ($q) use ($venueId) {
                $q->whereNull('venue_id')
                  ->orWhere('venue_id', $venueId);
            });
        }

        return $query->orderBy('sort_order')->get();
    }

    /**
     * Get formatted title with calculated MD-x date
     */
    public function getFormattedTitleAttribute()
    {
        $dateLabel = $this->title; // fallback to title
        
        if ($this->match && $this->match->match_date) {
            $daysOffset = 0;
            if (preg_match('/MD-?(\d+)/', $this->code, $matches)) {
                $daysOffset = (int)$matches[1];
                $calcDate = \Carbon\Carbon::parse($this->match->match_date)->subDays($daysOffset);
                $dateLabel = $calcDate->format('d/m/Y') . ' - ' . $this->code;
            } elseif ($this->code === 'MD') {
                $dateLabel = \Carbon\Carbon::parse($this->match->match_date)->format('d/m/Y') . ' - MD';
            }
        } else {
            // No match associated, just show code
            $dateLabel = $this->code;
        }

        return $dateLabel;
    }
}
