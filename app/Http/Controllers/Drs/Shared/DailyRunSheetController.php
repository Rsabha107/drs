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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $userFaIds = [];
        $userFas   = collect();
        if ($user->hasRole('Customer')) {
            $userFas   = $user->fa()->get(['functional_areas.id', 'functional_areas.title', 'functional_areas.fa_code']);
            $userFaIds = $userFas->pluck('id')->toArray();
        }

        // Sheet types available to this user but if SupreAdmin show all sheet types
        $sheetTypesQuery = DailyRunSheet::where('event_id', $event->id);
        if ($user->hasRole('Customer')) {
            $sheetTypesQuery->whereIn('functional_area_id', $userFaIds);
        }

        // $sheetTypesQuery = DailyRunSheet::where('event_id', $event->id);
        // if ($user->hasRole('Customer')) {
        //     $sheetTypesQuery->whereIn('functional_area_id', $userFaIds);
        // }
        $sheetTypes = $sheetTypesQuery->distinct()->orderBy('sheet_type')->pluck('sheet_type');

        return view('drs.drs.list', compact('event', 'matches', 'functionalAreas', 'userFaIds', 'userFas', 'sheetTypes'));
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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('Customer')) {
            // Customer sees only their own FA's sheets
            $query = DailyRunSheet::with(['venue', 'match', 'functionalArea'])
                ->where('event_id', $eventId)
                ->whereHas('functionalArea', function ($q) use ($user) {
                    $q->whereIn('id', $user->fa->pluck('id'));
                });
        } elseif ($user->hasRole('SuperAdmin')) {
            // SuperAdmin sees all sheets for the event
            $query = DailyRunSheet::with(['venue', 'match', 'functionalArea'])
                ->where('event_id', $eventId);
        } else {
            // default to no sheets
            $query = DailyRunSheet::whereRaw('0=1');
        }


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
            // 'gates_opening'      => 'nullable|date_format:H:i',
            // 'kick_off'           => 'nullable|date_format:H:i',
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

        if ($request->sheet_type === 'MD') {
            $this->populateMdTemplate($sheet->id);
        }

        return response()->json([
            'error'    => false,
            'message'  => 'Daily Run Sheet created successfully.',
            'redirect' => route('drs.drs.show', $sheet->id),
        ]);
    }

    public function show($id)
    {
        $sheet = DailyRunSheet::with(['event', 'venue', 'match', 'functionalArea', 'items'])
            ->findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('Customer')) {
            // Customer may only open a sheet that belongs to their own FA
            $userFaIds = $user->fa()->pluck('functional_areas.id');
            abort_unless($userFaIds->contains($sheet->functional_area_id), 403);
            $canEdit = true; // it's their sheet — they can add items and action their own rows
        } else {
            $canEdit = $user->hasRole('SuperAdmin');
        }

        return view('drs.drs.show', compact('sheet', 'canEdit'));
    }

    public function showList(Request $request, $id)
    {
        $eventId = session()->get('EVENT_ID');

        // Verify the run sheet belongs to the current event
        $sheet = DailyRunSheet::where('id', $id)
            ->where('event_id', $eventId)
            ->firstOrFail();

        /** @var \App\Models\User $authUser */
        $authUser  = Auth::user();
        $userFaIds = $authUser->hasRole('Customer')
            ? $authUser->fa()->pluck('functional_areas.id')->toArray()
            : [];

        $sort  = $request->input('sort', 'start_time');
        $order = $request->input('order', 'asc');
        $limit = max(1, min((int) $request->input('limit', 20), 200));

        $allowedSorts = ['id', 'title', 'start_time', 'end_time', 'countdown_to_ko', 'location'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'start_time';
        }

        // Show all items from every sheet that shares the same sheet_type within this event
        $query = DailyRunSheetItem::with('runSheet.functionalArea')
            ->whereHas('runSheet', function ($q) use ($eventId, $sheet) {
                $q->where('event_id', $eventId)
                  ->where('sheet_type', $sheet->sheet_type);
            });

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $total = $query->count();
        $rows  = $query->orderBy($sort, $order)->paginate($limit)->through(function ($item) use ($authUser, $userFaIds) {
            $sheetFaId = $item->runSheet?->functional_area_id;
            $canEdit   = $authUser->hasRole('SuperAdmin')
                || ($sheetFaId && in_array($sheetFaId, $userFaIds));

            return [
                'id'              => $item->id,
                'title'           => '<span class="fs-9 ps-3">' . e($item->title) . '</span>',
                'start_time'      => '<span class="fs-9">' . e($item->start_time ?? '-') . '</span>',
                'countdown_to_ko' => '<span class="fs-9">' . e($item->countdown_to_ko) . '</span>',
                'end_time'        => '<span class="fs-9">' . e($item->end_time ?? '-') . '</span>',
                'functional_area' => '<span class="fs-9">' . e($item->runSheet?->functionalArea?->title ?? '-') . '</span>',
                'location'        => '<span class="fs-9">' . e($item->location ?? '-') . '</span>',
                'description'     => '<span class="fs-9">' . e($item->description ?? '-') . '</span>',
                'row_color'       => $item->row_color,
                'can_edit'        => $canEdit,
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
            // 'gates_opening'      => 'nullable|date_format:H:i',
            // 'kick_off'           => 'nullable|date_format:H:i',
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

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        if ($authUser->hasRole('Customer')) {
            $parentSheet = DailyRunSheet::findOrFail($request->run_sheet_id);
            abort_unless(
                $parentSheet->functional_area_id &&
                $authUser->fa()->where('functional_areas.id', $parentSheet->functional_area_id)->exists(),
                403
            );
        }

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

        $item = DailyRunSheetItem::with('runSheet')->findOrFail($request->id);
        $this->authorize('update', $item);
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
        $item = DailyRunSheetItem::with('runSheet')->findOrFail($id);
        $this->authorize('delete', $item);
        $sheetId = $item->run_sheet_id;
        $item->delete();

        if (request()->expectsJson()) {
            return response()->json(['error' => false, 'message' => 'Item deleted.']);
        }

        return redirect()->route('drs.drs.show', $sheetId)
            ->with('message', 'Item deleted.')
            ->with('alert-type', 'success');
    }

    public function itemDuplicate($id)
    {
        $item = DailyRunSheetItem::with('runSheet')->findOrFail($id);
        $this->authorize('update', $item);

        $copy = $item->replicate();
        $copy->title = $item->title . ' (Copy)';
        $copy->save();

        return response()->json([
            'error'   => false,
            'message' => 'Item duplicated.',
            'item'    => [
                'id'              => $copy->id,
                'title'           => $copy->title,
                'start_time'      => $copy->start_time ? Carbon::parse($copy->start_time)->format('H:i') : '',
                'end_time'        => $copy->end_time ? Carbon::parse($copy->end_time)->format('H:i') : '',
                'countdown_to_ko' => $copy->countdown_to_ko ?? '',
                'functional_area' => $copy->functional_area ?? '',
                'location'        => $copy->location ?? '',
                'description'     => $copy->description ?? '',
                'row_color'       => $copy->row_color,
                'sort_order'      => $copy->sort_order,
            ],
        ]);
    }

    // ── MD Template ─────────────────────────────────────────────────────────

    private function populateMdTemplate(int $runSheetId): void
    {
        $now = now();
        $items = [
            ['title' => 'Workforce & Metro PSA Operational',                                                                             'row_color' => 'red',     'sort_order' => 10],
            ['title' => 'Venue Team Meeting',                                                                                            'row_color' => 'default', 'sort_order' => 20],
            ['title' => 'Temporary Traffic management & control measures on site as per agreed plans - Close Roads near stadium as per plans', 'row_color' => 'default', 'sort_order' => 30],
            ['title' => 'VOC OPEN',                                                                                                      'row_color' => 'default', 'sort_order' => 40],
            ['title' => 'TETRA CHECK',                                                                                                   'row_color' => 'default', 'sort_order' => 50],
            ['title' => '24h to GO - Ensure operational readiness in all areas and report any issues to VOC - KO-8',                     'row_color' => 'yellow',  'sort_order' => 60],
            ['title' => 'Accreditation Zoning Activation: KO-8',                                                                        'row_color' => 'red',     'sort_order' => 70],
            ['title' => 'MatchDay',                                                                                                      'row_color' => 'red',     'sort_order' => 80],
            ['title' => '30M to GO - Ensure operational readiness: KO -35',                                                             'row_color' => 'yellow',  'sort_order' => 90],
            ['title' => 'Frely Floodlights ON',                                                                                          'row_color' => 'default', 'sort_order' => 100],
            ['title' => 'GATES OPEN',                                                                                                    'row_color' => 'default', 'sort_order' => 110],
            ['title' => 'Fan Zone is Operational',                                                                                       'row_color' => 'default', 'sort_order' => 120],
            ['title' => 'TEAM B KIT VAN ARRIVAL',                                                                                        'row_color' => 'default', 'sort_order' => 130],
            ['title' => 'TEAM A KIT VAN ARRIVAL',                                                                                        'row_color' => 'default', 'sort_order' => 140],
            ['title' => 'TEAM A ARRIVAL',                                                                                                'row_color' => 'default', 'sort_order' => 150],
            ['title' => 'TEAM B ARRIVAL',                                                                                                'row_color' => 'default', 'sort_order' => 160],
            ['title' => 'Fan Zone is closed',                                                                                            'row_color' => 'default', 'sort_order' => 170],
            ['title' => 'Warm Up starts',                                                                                                'row_color' => 'default', 'sort_order' => 180],
            ['title' => 'Warm Up Finishes',                                                                                              'row_color' => 'default', 'sort_order' => 190],
            ['title' => 'Commentary starts',                                                                                             'row_color' => 'default', 'sort_order' => 200],
            ['title' => 'KICK OFF',                                                                                                      'row_color' => 'green',   'sort_order' => 210],
            ['title' => 'END OF FIRST HALF',                                                                                             'row_color' => 'default', 'sort_order' => 220],
            ['title' => 'START OF SECOND HALF',                                                                                          'row_color' => 'red',     'sort_order' => 230],
            ['title' => 'All Parkings ready for Egress Operation',                                                                       'row_color' => 'default', 'sort_order' => 240],
            ['title' => 'Official Match Attendance announcement',                                                                        'row_color' => 'yellow',  'sort_order' => 250],
            ['title' => 'Redeployment + Egress postmatch',                                                                               'row_color' => 'default', 'sort_order' => 260],
            ['title' => 'Egress Gates open',                                                                                             'row_color' => 'yellow',  'sort_order' => 270],
            ['title' => 'Final Whistle',                                                                                                 'row_color' => 'green',   'sort_order' => 280],
            ['title' => 'Fan Zone is Operational',                                                                                       'row_color' => 'default', 'sort_order' => 290],
            ['title' => 'Post match Press Conference',                                                                                   'row_color' => 'default', 'sort_order' => 300],
            ['title' => 'TEAM A has left the stadium',                                                                                   'row_color' => 'default', 'sort_order' => 310],
            ['title' => 'Team B has left the stadium',                                                                                   'row_color' => 'default', 'sort_order' => 320],
            ['title' => 'Referees have left the stadium',                                                                                'row_color' => 'default', 'sort_order' => 330],
            ['title' => 'Accreditation zoning deactivation',                                                                             'row_color' => 'default', 'sort_order' => 340],
            ['title' => 'VOC close/End of Operations',                                                                                   'row_color' => 'default', 'sort_order' => 350],
        ];

        $rows = array_map(fn($item) => array_merge($item, [
            'run_sheet_id' => $runSheetId,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]), $items);

        DailyRunSheetItem::insert($rows);
    }

    // ── Matches by Venue ─────────────────────────────────────────────────────

    public function matchesByVenue($venueId)
    {
        $eventId = session()->get('EVENT_ID');
        $matches = EventMatch::where('event_id', $eventId)
            ->where('venue_id', $venueId)
            ->orderBy('match_date')
            ->get(['id', 'match_number', 'match_date', 'pma1', 'pma2', 'gates_opening', 'kick_off']);

        return response()->json($matches);
    }

    // ── Export ───────────────────────────────────────────────────────────────

    public function export($id)
    {
        $sheet = DailyRunSheet::findOrFail($id);
        $filename = 'DailyRunSheet_' . $sheet->sheet_type . '_' . $sheet->run_date . '.xlsx';

        return Excel::download(new DailyRunSheetExport($id), $filename);
    }

        public function pickEvent(Request $request)
    {
        // $events = Event::all();
        // $this->switch($request->event_id);
        // return view('vapp.admin.booking.pick', compact('events'));
        if ($request->event_id) {
            // appLog('Event ID: ' . $request->event_id);
            if (Event::findOrFail($request->event_id) && !session()->has('EVENT_ID')) {
                // appLog('Inside if statement Event ID: ' . $request->event_id);

                session()->put('EVENT_ID', $request->event_id);
                session()->put('VENUE_ID', $request->venue_id);
                // appLog('session EVENT_ID: ' . session()->get('EVENT_ID'));
                // appLog('before redirect');
                // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully.');
                return redirect()->route('drs.drs.index')->with('message', 'Event Switched.');
                // return back()->with('message', 'Event Switched.');
            }
        }
        //  else {
        // return back()->with('error', 'Workspace not found.');
        // return redirect()->route('tracki.project.show.card')->with('error', 'Workspace not found.');
        // appLog('event_id is null');
        return redirect()->route('drs.drs.index')->with('error', 'Event not found.');
        // }
    }
}
