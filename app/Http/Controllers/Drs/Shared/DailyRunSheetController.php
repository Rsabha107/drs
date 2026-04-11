<?php

namespace App\Http\Controllers\Drs\Shared;

use App\Exports\DailyRunSheetExport;
use App\Http\Controllers\Controller;
use App\Models\Drs\DailyRunSheet;
use App\Models\Drs\DailyRunSheetItem;
use App\Models\Drs\Event;
use App\Models\Drs\EventMatch;
use App\Models\Drs\FunctionalArea;
use App\Models\Drs\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Maatwebsite\Excel\Facades\Excel;

class DailyRunSheetController extends Controller
{
    public function index()
    {
        $event = Event::findOrFail(session()->get('EVENT_ID'));
        $matches = EventMatch::where('event_id', $event->id)->orderBy('match_date')->get();
        $functionalAreas = FunctionalArea::orderBy('fa_code')->get();

        return view('drs.drs.list', compact('event', 'matches', 'functionalAreas'));
    }

    public function list(Request $request)
    {
        $eventId = session()->get('EVENT_ID');

        $sort  = $request->input('sort', 'run_date');
        $order = $request->input('order', 'desc');
        $limit = max(1, min((int) $request->input('limit', 20), 200));


        $allowedSorts = ['id', 'sheet_type', 'run_date', 'gates_opening', 'kick_off'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'run_date';
        }

        $query = DailyRunSheet::with(['venue', 'match', 'functionalArea'])
            ->where('event_id', $eventId);

        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->venue_id);
        }
        if ($request->filled('sheet_type')) {
            $query->where('sheet_type', $request->sheet_type);
        }
        if ($request->filled('functional_area_id')) {
            $query->where('functional_area_id', $request->functional_area_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('sheet_type', 'like', "%{$s}%")
                    ->orWhere('run_date', 'like', "%{$s}%")
                    ->orWhereHas('venue', fn($q2) => $q2->where('short_name', 'like', "%{$s}%"));
            });
        }

        $total = $query->count();
        $rows  = $query->orderBy($sort, $order)->paginate($limit)->through(function ($s) {
            return [
                'id'               => $s->id,
                'sheet_type'       => '<span class="badge bg-primary">' . e($s->sheet_type) . '</span>',
                'venue'            => '<span class="fs-9">' . e($s->venue?->short_name ?? '-') . '</span>',
                'match'            => '<span class="fs-9">' . e($s->match ? $s->match->match_number : '-') . '</span>',
                'teams'            => '<span class="fs-9">' . e($s->match ? $s->match->pma1 . ' vs ' . $s->match->pma2 : '-') . '</span>',
                'functional_area'  => '<span class="fs-9">' . e($s->functionalArea?->title ?? '-') . '</span>',
                'run_date'         => '<span class="fs-9">' . e($s->run_date_dmy) . '</span>',
                'gates_opening'    => '<span class="fs-9">' . ($s->gates_opening ? \Carbon\Carbon::parse($s->gates_opening)->format('H:i') : '-') . '</span>',
                'kick_off'         => '<span class="fs-9">' . ($s->kick_off ? \Carbon\Carbon::parse($s->kick_off)->format('H:i') : '-') . '</span>',
                'items_count'      => '<span class="badge bg-secondary">' . $s->items()->count() . '</span>',
            ];
        });

        return response()->json([
            'total' => $total,
            'rows'  => $rows->items(),
        ]);
    }

    public function create()
    {
        $event   = Event::findOrFail(session()->get('EVENT_ID'));
        $venues  = $event->venues;
        $matches = EventMatch::where('event_id', $event->id)->orderBy('match_date')->get();

        return view('drs.drs.create', compact('event', 'venues', 'matches'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'venue_id'           => 'required|integer',
            'sheet_type'         => 'required|string|max:50',
            'run_date'           => 'required|date',
            'gates_opening'      => 'nullable|date_format:H:i',
            'kick_off'           => 'nullable|date_format:H:i',
            'match_id'           => 'nullable|integer',
            'functional_area_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $sheet = DailyRunSheet::create([
            'event_id'           => session()->get('EVENT_ID'),
            'venue_id'           => $request->venue_id,
            'match_id'           => $request->match_id ?: null,
            'functional_area_id' => $request->functional_area_id ?: null,
            'sheet_type'         => $request->sheet_type,
            'run_date'           => $request->run_date,
            'gates_opening'      => $request->gates_opening ?: null,
            'kick_off'           => $request->kick_off ?: null,
            'created_by'         => Auth::id(),
        ]);

        return response()->json([
            'error'    => false,
            'message'  => 'Daily Run Sheet created successfully.',
            'redirect' => route('drs.drs.show', $sheet->id),
        ]);
    }

    public function show($id)
    {
        $sheet = DailyRunSheet::with(['event', 'venue', 'match', 'items'])->findOrFail($id);
        return view('drs.drs.show', compact('sheet'));
    }



    public function showList(Request $request, $id)
    {
        $eventId = session()->get('EVENT_ID');

        $sort  = $request->input('sort', 'start_time');
        $order = $request->input('order', 'desc');
        $limit = max(1, min((int) $request->input('limit', 20), 200));


        $allowedSorts = ['id', 'sheet_type', 'run_date', 'gates_opening', 'kick_off', 'start_time'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'start_time';
        }

        $query = DailyRunSheetItem::where('run_sheet_id', $id);
        // ->where('event_id', $eventId);


        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('sheet_type', 'like', "%{$s}%")
                    ->orWhere('run_date', 'like', "%{$s}%")
                    ->orWhereHas('venue', fn($q2) => $q2->where('short_name', 'like', "%{$s}%"));
            });
        }

        $total = $query->count();
        $rows  = $query->orderBy($sort, $order)->paginate($limit)->through(function ($s) {
            return [
                'id'            => $s->id,
                'title'    => '<span class="fs-9 ps-3">' . e($s->title) . '</span>',
                'start_time'         => '<span class="fs-9">' . e($s->start_time ?? '-') . '</span>',
                'countdown_to_ko'      => '<span class="fs-9">' . e($s->countdown_to_ko) . '</span>',
                'end_time' => '<span class="fs-9">' . e($s->end_time ?? '-') . '</span>',
                'functional_area' => '<span class="fs-9">' . e($s->runSheet->functionalArea->title ?? '-') . '</span>',
                'location' => '<span class="fs-9">' . e($s->location ?? '-') . '</span>',
                'description' => '<span class="fs-9">' . e($s->description ?? '-') . '</span>',
            ];
        });

        return response()->json([
            'total' => $total,
            'rows'  => $rows->items(),
        ]);
    }

    public function get($id)
    {
        $sheet = DailyRunSheet::findOrFail($id);

        return response()->json([
            'id'                 => $sheet->id,
            'venue_id'           => $sheet->venue_id,
            'match_id'           => $sheet->match_id,
            'functional_area_id' => $sheet->functional_area_id,
            'sheet_type'         => $sheet->sheet_type,
            'run_date'           => $sheet->run_date,
            'gates_opening'      => $sheet->gates_opening ? Carbon::parse($sheet->gates_opening)->format('H:i') : '',
            'kick_off'           => $sheet->kick_off ? Carbon::parse($sheet->kick_off)->format('H:i') : '',
        ]);
    }

    public function update(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'id'                 => 'required|integer|exists:daily_run_sheets,id',
            'venue_id'           => 'required|integer',
            'sheet_type'         => 'required|string|max:50',
            'run_date'           => 'required|date',
            'gates_opening'      => 'nullable|date_format:H:i',
            'kick_off'           => 'nullable|date_format:H:i',
            'match_id'           => 'nullable|integer',
            'functional_area_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $sheet = DailyRunSheet::findOrFail($request->id);
        $sheet->update([
            'venue_id'           => $request->venue_id,
            'match_id'           => $request->match_id ?: null,
            'functional_area_id' => $request->functional_area_id ?: null,
            'sheet_type'         => $request->sheet_type,
            'run_date'           => $request->run_date,
            'gates_opening'      => $request->gates_opening ?: null,
            'kick_off'           => $request->kick_off ?: null,
        ]);

        return response()->json([
            'error'   => false,
            'message' => 'Daily Run Sheet updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        DailyRunSheet::findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['error' => false, 'message' => 'Item deleted.']);
        }

        return redirect()->route('drs.drs.index')
            ->with('message', 'Daily Run Sheet deleted.')
            ->with('alert-type', 'success');
    }

    // ── Items ────────────────────────────────────────────────────────────────

    public function itemCreate($runSheetId)
    {
        $sheet = DailyRunSheet::findOrFail($runSheetId);
        return view('drs.drs.item_form', compact('sheet'));
    }

    public function itemStore(Request $request)
    {
        $request->validate([
            'run_sheet_id'    => 'required|integer|exists:daily_run_sheets,id',
            'title'           => 'required|string|max:255',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'functional_area' => 'nullable|string|max:255',
            'location'        => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'row_color'       => 'required|in:default,red,yellow,green',
            'sort_order'      => 'nullable|integer',
            'countdown_to_ko'  => 'nullable|string',
        ]);

        $item = DailyRunSheetItem::create($request->only([
            'run_sheet_id',
            'title',
            'start_time',
            'end_time',
            'functional_area',
            'location',
            'description',
            'row_color',
            'sort_order',
            'countdown_to_ko',
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'error'   => false,
                'message' => 'Item added successfully.',
                'item'    => [
                    'id'              => $item->id,
                    'title'           => $item->title,
                    'start_time'      => $item->start_time ? Carbon::parse($item->start_time)->format('H:i') : '',
                    'end_time'        => $item->end_time ? Carbon::parse($item->end_time)->format('H:i') : '',
                    'countdown_to_ko' => $item->countdown_to_ko ?? '',
                    'functional_area' => $item->functional_area ?? '',
                    'location'        => $item->location ?? '',
                    'description'     => $item->description ?? '',
                    'row_color'       => $item->row_color,
                    'edit_url'        => route('drs.drs.item.edit', $item->id),
                    'destroy_url'     => route('drs.drs.item.destroy', $item->id),
                ],
            ]);
        }

        return redirect()->route('drs.drs.show', $request->run_sheet_id)
            ->with('message', 'Item added successfully.')
            ->with('alert-type', 'success');
    }

    public function itemGet($id)
    {
        $item = DailyRunSheetItem::findOrFail($id);

        return response()->json([
            'id'              => $item->id,
            'title'           => $item->title,
            'start_time'      => $item->start_time ? Carbon::parse($item->start_time)->format('H:i') : '',
            'end_time'        => $item->end_time ? Carbon::parse($item->end_time)->format('H:i') : '',
            'functional_area' => $item->functional_area ?? '',
            'location'        => $item->location ?? '',
            'description'     => $item->description ?? '',
            'row_color'       => $item->row_color,
            'sort_order'      => $item->sort_order ?? 0,
        ]);
    }

    public function itemEdit($id)
    {
        $item  = DailyRunSheetItem::findOrFail($id);
        $sheet = $item->runSheet;
        return view('drs.drs.item_form', compact('item', 'sheet'));
    }

    public function itemUpdate(Request $request)
    {
        $request->validate([
            'id'              => 'required|integer|exists:daily_run_sheet_items,id',
            'title'           => 'required|string|max:255',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'functional_area' => 'nullable|string|max:255',
            'location'        => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'row_color'       => 'required|in:default,red,yellow,green',
            'sort_order'      => 'nullable|integer',
            'countdown_to_ko' => 'nullable|string',
        ]);

        $item = DailyRunSheetItem::findOrFail($request->id);
        $item->update($request->only([
            'title',
            'start_time',
            'end_time',
            'functional_area',
            'location',
            'description',
            'row_color',
            'sort_order',
            'countdown_to_ko',
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'error'   => false,
                'message' => 'Item updated successfully.',
                'item'    => [
                    'id'              => $item->id,
                    'title'           => $item->title,
                    'start_time'      => $item->start_time ? Carbon::parse($item->start_time)->format('H:i') : '',
                    'end_time'        => $item->end_time ? Carbon::parse($item->end_time)->format('H:i') : '',
                    'functional_area' => $item->functional_area ?? '',
                    'location'        => $item->location ?? '',
                    'description'     => $item->description ?? '',
                    'row_color'       => $item->row_color,
                    'countdown_to_ko' => $item->countdown_to_ko,
                ],
            ]);
        }

        return redirect()->route('drs.drs.show', $item->run_sheet_id)
            ->with('message', 'Item updated successfully.')
            ->with('alert-type', 'success');
    }

    public function itemDestroy($id)
    {
        $item = DailyRunSheetItem::findOrFail($id);
        $sheetId = $item->run_sheet_id;
        $item->delete();

        if (request()->expectsJson()) {
            return response()->json(['error' => false, 'message' => 'Item deleted.']);
        }

        return redirect()->route('drs.drs.show', $sheetId)
            ->with('message', 'Item deleted.')
            ->with('alert-type', 'success');
    }

    // ── Matches by Venue ─────────────────────────────────────────────────────

    public function matchesByVenue($venueId)
    {
        $eventId = session()->get('EVENT_ID');
        $matches = EventMatch::where('event_id', $eventId)
            ->where('venue_id', $venueId)
            ->orderBy('match_date')
            ->get(['id', 'match_number', 'match_date', 'pma1', 'pma2']);

        return response()->json($matches);
    }

    // ── Export ───────────────────────────────────────────────────────────────

    public function export($id)
    {
        $sheet = DailyRunSheet::findOrFail($id);
        $filename = 'DailyRunSheet_' . $sheet->sheet_type . '_' . $sheet->run_date . '.xlsx';

        return Excel::download(new DailyRunSheetExport($id), $filename);
    }
}
