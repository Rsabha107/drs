<?php

namespace App\Exports;

use App\Models\Drs\DailyRunSheet;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FlatListExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $eventId;
    protected $venueId;
    protected $matchId;
    protected $sheetType;

    public function __construct($eventId, $venueId, $matchId, $sheetType = null)
    {
        $this->eventId   = $eventId;
        $this->venueId   = $venueId;
        $this->matchId   = $matchId;
        $this->sheetType = $sheetType;
    }

    public function view(): View
    {
        $query = DailyRunSheet::with(['event', 'venue', 'match', 'functionalArea', 'items'])
            ->where('event_id', $this->eventId)
            ->where('venue_id', $this->venueId)
            ->where('match_id', $this->matchId);

        if ($this->sheetType) {
            $query->where('sheet_type', $this->sheetType);
        }

        $sheets = $query->get();

        $firstSheet  = $sheets->first();
        $koFormatted = $firstSheet?->kick_off
            ? Carbon::parse($firstSheet->kick_off)->format('H:i')
            : null;

        // Flatten all items across sheets, sorted by start_time
        $items = $sheets->flatMap(function ($sheet) {
            return $sheet->items->map(function ($item) use ($sheet) {
                $item->_parentSheet = $sheet;
                return $item;
            });
        })->sortBy(function ($item) {
            return $item->start_time ?? '99:99';
        })->values();

        return view('drs.drs.exports.flat-list', [
            'firstSheet'  => $firstSheet,
            'items'       => $items,
            'koFormatted' => $koFormatted,
            'sheetType'   => $this->sheetType,
        ]);
    }

    public function title(): string
    {
        return 'Combined Run Sheet';
    }
}
