<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Exports\FlatListExport;
use App\Http\Controllers\Controller;
use App\Models\Drs\DailyRunSheet;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Drs\DailyRunSheetItem;
use App\Models\Drs\Event;
use Carbon\Carbon;
use App\Models\Drs\EventMatch;
use App\Models\Drs\FunctionalArea;
use App\Models\Drs\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyRunSheetController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $userFaIds = [];
        $userFas   = collect();
        if ($user->hasRole('Customer')) {
            $userFas   = $user->fa()->get(['functional_areas.id', 'functional_areas.title', 'functional_areas.fa_code']);
            $userFaIds = $userFas->pluck('id')->toArray();
        } elseif ($user->hasRole('SuperAdmin')) {
            $userFas   = FunctionalArea::orderBy('fa_code')->get();
            $userFaIds = $userFas->pluck('id')->toArray();
        }

        $event = Event::findOrFail(session()->get('EVENT_ID'));
        $functionalAreas = FunctionalArea::orderBy('fa_code')->get();
        // Sheet types available to this user but if SupreAdmin show all sheet types
        $sheetTypesQuery = DailyRunSheet::where('event_id', $event->id);
        if ($user->hasRole('Customer')) {
            $sheetTypesQuery->whereIn('functional_area_id', $userFaIds);
        }

        $sheetTypes = $sheetTypesQuery->pluck('sheet_type')->filter()->unique()->sort()->values();

        return view('drs.drs.list', compact('event', 'functionalAreas', 'userFaIds', 'userFas', 'sheetTypes'));
    }

    public function list(Request $request)
    {
        // Delegate to shared controller
        return app(\App\Http\Controllers\Drs\Shared\DailyRunSheetController::class)->list($request);
    }


    public function flatListExport(Request $request)
    {
        $eventId   = session()->get('EVENT_ID');
        $venueId   = $request->input('venue_id');
        $matchId   = $request->input('match_id');
        $sheetType = $request->input('sheet_type');

        $export   = new FlatListExport($eventId, $venueId, $matchId, $sheetType);
        $suffix   = $sheetType ? '_' . $sheetType : '';
        $filename = 'CombinedRunSheet_V' . $venueId . '_M' . $matchId . $suffix . '.xlsx';

        return Excel::download($export, $filename);
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
        $eventId   = session()->get('EVENT_ID');
        $event     = Event::findOrFail($eventId);
        $venueId   = $request->input('venue_id');
        $sheetType = $request->input('sheet_type');
        $matchId   = $request->input('match_id');

        // Only venues that have run sheets for this event
        $venues = Venue::whereHas('matches', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->orderBy('short_name')->get();

        // Sheet types available for the selected venue
        $sheetTypes = collect();
        if ($venueId) {
            $sheetTypes = DailyRunSheet::where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->orderBy('sheet_type')
                ->pluck('sheet_type')
                ->filter()->unique()->values();
        }

        // Matches that have a sheet of the selected type for this venue (match is optional)
        $matches = collect();
        if ($venueId && $sheetType) {
            $matchIds = DailyRunSheet::where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->where('sheet_type', $sheetType)
                ->whereNotNull('match_id')
                ->pluck('match_id');

            $matches = EventMatch::whereIn('id', $matchIds)
                ->orderBy('match_date')
                ->get();
        }

        $sheets      = collect();
        $matchHeader = null;

        if ($venueId && $sheetType) {
            $sheets = DailyRunSheet::with(['venue', 'match', 'functionalArea', 'items'])
                ->where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->where('sheet_type', $sheetType)
                ->when($matchId, fn($q) => $q->where('match_id', $matchId))
                ->get();

            $matchHeader = $sheets->first();
        }

        return view('drs.admin.report.flat-list', compact(
            'event',
            'venues',
            'matches',
            'matchHeader',
            'venueId',
            'matchId',
            'sheets',
            'sheetTypes',
            'sheetType'
        ));
    }

    public function sheetTypesByMatch(Request $request)
    {
        $eventId   = session()->get('EVENT_ID');
        $venueId   = $request->input('venue_id');
        $matchId   = $request->input('match_id');
        $sheetType = $request->input('sheet_type');

        // When list=matches, return matches that have a sheet of the given type for the venue
        if ($request->input('list') === 'matches' && $venueId && $sheetType) {
            $matchIds = DailyRunSheet::where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->where('sheet_type', $sheetType)
                ->whereNotNull('match_id')
                ->pluck('match_id');

            $matches = EventMatch::whereIn('id', $matchIds)
                ->orderBy('match_date')
                ->get(['id', 'match_number', 'match_date', 'pma1', 'pma2']);

            return response()->json($matches);
        }

        // Default: return sheet types for the given venue (and optional match)
        $types = DailyRunSheet::where('event_id', $eventId)
            ->when($venueId, fn($q) => $q->where('venue_id', $venueId))
            ->when($matchId, fn($q) => $q->where('match_id', $matchId))
            ->orderBy('sheet_type')
            ->pluck('sheet_type')
            ->filter()
            ->unique()
            ->values();

        return response()->json($types);
    }

    public function flatListData(Request $request)
    {
        $eventId = session()->get('EVENT_ID');
        $venueId = $request->input('venue_id');
        $matchId = $request->input('match_id');

        $sort  = $request->input('sort', 'start_time');
        $order = $request->input('order', 'asc');
        $rawLimit = (int) $request->input('limit', 25);
        $limit    = $rawLimit >= 10000 ? 10000 : max(1, min($rawLimit, 500));

        $allowedSorts = ['id', 'title', 'start_time', 'end_time', 'countdown_to_ko', 'location'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'start_time';
        }

        $sheetType = $request->input('sheet_type');

        // Collect run sheet IDs for this event+venue+match
        $sheetIds = DailyRunSheet::where('event_id', $eventId)
            ->when($venueId, fn($q) => $q->where('venue_id', $venueId))
            ->when($matchId, fn($q) => $q->where('match_id', $matchId))
            ->when($sheetType, fn($q) => $q->where('sheet_type', $sheetType))
            ->pluck('id');

        // Get the KO time from the first matching sheet
        $koTime = DailyRunSheet::whereIn('id', $sheetIds)->value('kick_off');
        $koFormatted = $koTime ? Carbon::parse($koTime)->format('H:i') : null;

        $query = DailyRunSheetItem::with(['runSheet.functionalArea'])
            ->whereIn('run_sheet_id', $sheetIds);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%")
                    ->orWhere('functional_area', 'like', "%{$s}%");
            });
        }

        $total = $query->count();

        $nullableColumns = ['start_time', 'end_time', 'countdown_to_ko', 'location'];
        if (in_array($sort, $nullableColumns)) {
            $query->orderByRaw("ISNULL(`{$sort}`) ASC, `{$sort}` {$order}");
        } else {
            $query->orderBy($sort, $order);
        }

        $rows  = $query->paginate($limit)->through(function ($item) use ($koFormatted) {
            $fa    = $item->runSheet?->functionalArea;
            $faLabel = $fa?->title ?? $fa?->name ?? ($item->functional_area ?? '-');

            // Calculate countdown
            $countdown = $item->countdown_to_ko ?? '';
            $startFmt  = $item->start_time ? Carbon::parse($item->start_time)->format('H:i') : null;
            if ($startFmt && $koFormatted) {
                [$kh, $km] = explode(':', $koFormatted);
                [$sh, $sm] = explode(':', $startFmt);
                $diff = ((int)$sh * 60 + (int)$sm) - ((int)$kh * 60 + (int)$km);
                if ($diff === 0) {
                    $countdown = 'KO';
                } else {
                    $sign  = $diff > 0 ? '+' : '-';
                    $abs   = abs($diff);
                    $label = 'KO' . $sign;
                    if (intdiv($abs, 60) > 0) $label .= intdiv($abs, 60) . 'h';
                    if ($abs % 60 > 0)        $label .= ($abs % 60) . 'm';
                    $countdown = $label;
                }
            }

            return [
                'id'              => $item->id,
                'title'           => '<span class="fs-9">' . e($item->title) . '</span>',
                'start_time'      => '<span class="fs-9 text-nowrap">' . ($startFmt ?? '-') . '</span>',
                'countdown_to_ko' => '<span class="fs-9 fst-italic text-nowrap">' . e($countdown) . '</span>',
                'end_time'        => '<span class="fs-9 text-nowrap">' . ($item->end_time ? Carbon::parse($item->end_time)->format('H:i') : '-') . '</span>',
                'functional_area' => '<span class="fs-9">' . e($faLabel) . '</span>',
                'location'        => '<span class="fs-9">' . e($item->location ?? '-') . '</span>',
                'description'     => '<span class="fs-9">' . e($item->description ?? '-') . '</span>',
                'row_color'       => $item->row_color ?? 'default',
            ];
        });

        return response()->json([
            'total' => $total,
            'rows'  => $rows->items(),
        ]);
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
