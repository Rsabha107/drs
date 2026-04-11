<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drs\DailyRunSheet;
use App\Models\Drs\Event;
use App\Models\Drs\EventMatch;
use App\Models\Drs\FunctionalArea;
use App\Models\Drs\Venue;
use Illuminate\Http\Request;

class DailyRunSheetController extends Controller
{
    public function index()
    {
        $event = Event::findOrFail(session()->get('EVENT_ID'));
        $functionalAreas = FunctionalArea::orderBy('fa_code')->get();

        return view('drs.drs.list', compact('event', 'functionalAreas'));
    }

    public function list(Request $request)
    {
        // Delegate to shared controller
        return app(\App\Http\Controllers\Drs\Shared\DailyRunSheetController::class)->list($request);
    }


    public function venueMatchView()
    {
        $eventId = session()->get('EVENT_ID');
        $event   = Event::findOrFail($eventId);

        // Load all run sheets for the event with their relationships and items
        $sheets = DailyRunSheet::with(['venue', 'match', 'functionalArea', 'items'])
            ->where('event_id', $eventId)
            ->orderBy('venue_id')
            ->orderBy('match_id')
            ->orderBy('sheet_type')
            ->get();

        // Group: venue → match_id → sheets
        $grouped = $sheets->groupBy('venue_id')->map(function ($venueSheets) {
            return $venueSheets->groupBy('match_id');
        });

        return view('drs.admin.report.venue-match', compact('event', 'grouped'));
    }

    public function flatListView(Request $request)
    {
        $eventId = session()->get('EVENT_ID');
        $event   = Event::findOrFail($eventId);

        // Only venues that have run sheets for this event
        $venues = Venue::whereHas('matches', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->orderBy('short_name')->get();

        $venueId = $request->input('venue_id');
        $matchId = $request->input('match_id');

        $matches     = collect();
        $matchHeader = null;
        $items       = collect();

        if ($venueId) {
            $matches = EventMatch::where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->orderBy('match_date')
                ->get();
        }

        $sheets = collect();

        if ($venueId && $matchId) {
            $sheets = DailyRunSheet::with(['venue', 'match', 'functionalArea', 'items'])
                ->where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->where('match_id', $matchId)
                ->get();

            $firstSheet = $sheets->first();
            if ($firstSheet) {
                $matchHeader = $firstSheet;
            }

            // Flatten all items from all sheets, attach parent sheet for FA/KO context
            $items = $sheets->flatMap(function ($sheet) {
                return $sheet->items->map(function ($item) use ($sheet) {
                    $item->_parentSheet = $sheet;
                    return $item;
                });
            })->sortBy(function ($item) {
                return $item->start_time ?? '99:99';
            })->values();
        }

        return view('drs.admin.report.flat-list', compact(
            'event', 'venues', 'matches', 'matchHeader', 'items', 'venueId', 'matchId', 'sheets'
        ));
    }

    public function switch($id)
    {
        if ($id) {
            if (Event::findOrFail($id)) {
                appLog('Event ID: ' . $id);

                session()->put('EVENT_ID', $id);
                appLog('Event ID: ' . session()->get('EVENT_ID'));
                // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully.');
                return redirect()->route('drs.drs.index')->with('message', 'Event Switched.');
                // return back()->with('message', 'Event Switched.');
            } else {
                // return back()->with('error', 'Workspace not found.');
                // return redirect()->route('tracki.project.show.card')->with('error', 'Workspace not found.');
                return back()->with('error', 'Event not found.');
            }
        } else {
            session()->forget('EVENT_ID');
            // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully. now showing all workspace data');
            return back()->withInput();
        }
    }
}
