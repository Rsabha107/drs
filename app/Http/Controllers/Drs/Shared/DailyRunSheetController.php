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
        } elseif ($user->hasRole('SuperAdmin')) {
            $userFas   = FunctionalArea::orderBy('fa_code')->get();
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
                'sheet_type'       => '<span class="badge bg-primary">' . e($s->run_date_dmy . ' ' . $s->sheet_type) . '</span>',
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

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('Customer') && $request->sheet_type === 'MD') {
            return response()->json([
                'error'   => true,
                'message' => 'Customers may not create MD sheet types.',
            ], 403);
        }

        $eventId          = session()->get('EVENT_ID');
        $matchId          = $request->match_id ?: null;
        $functionalAreaId = $request->functional_area_id ?: null;

        // MD with no FA: auto-create three sheets (CMP, SSI, VUM)
        if ($request->sheet_type === 'MD' && !$functionalAreaId) {
            return $this->createMdTriple($request, $eventId, $matchId);
        }

        $duplicate = DailyRunSheet::where('event_id', $eventId)
            ->where('venue_id', $request->venue_id)
            ->where('sheet_type', $request->sheet_type)
            ->where('match_id', $matchId)
            ->where('functional_area_id', $functionalAreaId)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'error'   => true,
                'message' => 'A Daily Run Sheet for this match, sheet type, and functional area already exists.',
            ], 422);
        }

        $sheet = DailyRunSheet::create([
            'event_id'           => $eventId,
            'venue_id'           => $request->venue_id,
            'match_id'           => $matchId,
            'functional_area_id' => $functionalAreaId,
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

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('Customer') && $request->sheet_type === 'MD') {
            return response()->json([
                'error'   => true,
                'message' => 'Customers may not use MD sheet types.',
            ], 403);
        }

        $matchId          = $request->match_id ?: null;
        $functionalAreaId = $request->functional_area_id ?: null;

        $duplicate = DailyRunSheet::where('event_id', session()->get('EVENT_ID'))
            ->where('venue_id', $request->venue_id)
            ->where('sheet_type', $request->sheet_type)
            ->where('match_id', $matchId)
            ->where('functional_area_id', $functionalAreaId)
            ->where('id', '!=', $request->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'error'   => true,
                'message' => 'A Daily Run Sheet for this match, sheet type, and functional area already exists.',
            ], 422);
        }

        $sheet = DailyRunSheet::findOrFail($request->id);
        $sheet->update([
            'venue_id'           => $request->venue_id,
            'match_id'           => $matchId,
            'functional_area_id' => $functionalAreaId,
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

    // ── Copy items from another DRS ──────────────────────────────────────────

    public function copySourceList($id)
    {
        $sheet   = DailyRunSheet::findOrFail($id);
        $eventId = $sheet->event_id;

        $sheets = DailyRunSheet::with(['venue', 'functionalArea'])
            ->where('event_id', $eventId)
            ->where('id', '!=', $id)
            ->orderBy('sheet_type')
            ->get()
            ->map(fn($s) => [
                'id'             => $s->id,
                'label'          => implode(' · ', array_filter([
                    $s->sheet_type,
                    $s->venue?->short_name,
                    $s->functionalArea?->fa_code,
                    $s->run_date_dmy,
                ])),
                'items_count'    => $s->items()->count(),
            ]);

        return response()->json($sheets);
    }

    public function copyItemsFrom(Request $request, $id)
    {
        $request->validate(['source_id' => 'required|integer|exists:daily_run_sheets,id']);

        $target = DailyRunSheet::findOrFail($id);
        $source = DailyRunSheet::with('items')->findOrFail($request->source_id);

        $faLabel = $target->functionalArea
            ? $target->functionalArea->fa_code . ' — ' . $target->functionalArea->title
            : null;

        $now  = now();
        $rows = $source->items->map(fn($item) => [
            'run_sheet_id'    => $target->id,
            'title'           => $item->title,
            'start_time'      => $item->start_time,
            'end_time'        => $item->end_time,
            'countdown_to_ko' => $item->countdown_to_ko,
            'functional_area' => $faLabel ?? $item->functional_area,
            'location'        => $item->location,
            'description'     => $item->description,
            'row_color'       => $item->row_color,
            'sort_order'      => $item->sort_order,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->toArray();

        DailyRunSheetItem::insert($rows);

        return response()->json([
            'error'   => false,
            'message' => count($rows) . ' items copied successfully.',
        ]);
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

    // ── MD Triple creation ───────────────────────────────────────────────────

    private function createMdTriple(Request $request, string $eventId, ?int $matchId): \Illuminate\Http\JsonResponse
    {
        $faCodes = ['CMP', 'SSI', 'VUM'];
        $fas     = FunctionalArea::whereIn('fa_code', $faCodes)->get()->keyBy('fa_code');

        $missing = array_diff($faCodes, $fas->keys()->toArray());
        if (!empty($missing)) {
            return response()->json([
                'error'   => true,
                'message' => 'Required functional areas not found: ' . implode(', ', $missing) . '. Please create them first.',
            ], 422);
        }

        foreach ($faCodes as $code) {
            $exists = DailyRunSheet::where('event_id', $eventId)
                ->where('venue_id', $request->venue_id)
                ->where('sheet_type', 'MD')
                ->where('match_id', $matchId)
                ->where('functional_area_id', $fas[$code]->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'error'   => true,
                    'message' => "An MD run sheet for {$code} already exists for this match.",
                ], 422);
            }
        }

        foreach ($faCodes as $code) {
            $sheet = DailyRunSheet::create([
                'event_id'           => $eventId,
                'venue_id'           => $request->venue_id,
                'match_id'           => $matchId,
                'functional_area_id' => $fas[$code]->id,
                'sheet_type'         => 'MD',
                'run_date'           => $request->run_date,
                'gates_opening'      => $request->gates_opening ?: null,
                'kick_off'           => $request->kick_off ?: null,
                'created_by'         => Auth::id(),
            ]);

            $this->populateMdTemplate($sheet->id, $code);
        }

        return response()->json([
            'error'    => false,
            'message'  => '3 MD Run Sheets created (CMP, SSI, VUM).',
            'redirect' => route('drs.drs.index'),
        ]);
    }

    // ── MD Template ─────────────────────────────────────────────────────────

    /**
     * Colour → Functional Area mapping:
     *   green  → CMP (Competition Management)
     *   red    → SSI (Security Systems Integration)
     *   yellow → VUM (Venue Management)
     */
    private function populateMdTemplate(int $runSheetId, string $faCode = 'ALL'): void
    {
        // Items with explicit KO-relative countdowns where derivable from title
        $allItems = [
            ['title' => 'Workforce & Metro PSA Operational',                                                                                  'row_color' => 'red',    'sort_order' => 10,  'countdown_to_ko' => null],
            ['title' => 'Venue Team Meeting',                                                                                                 'row_color' => 'yellow', 'sort_order' => 20,  'countdown_to_ko' => null],
            ['title' => 'Temporary Traffic management & control measures on site as per agreed plans - Close Roads near stadium as per plans', 'row_color' => 'yellow', 'sort_order' => 30,  'countdown_to_ko' => null],
            ['title' => 'VOC OPEN: KO-5',                                                                                                     'row_color' => 'yellow', 'sort_order' => 40,  'countdown_to_ko' => 'KO-5h'],
            ['title' => 'TETRA CHECK-INS',                                                                                                    'row_color' => 'yellow', 'sort_order' => 50,  'countdown_to_ko' => null],
            ['title' => '1Hr to GO - Ensure operational readiness in all areas and report any issues to VOC : KO-4',                          'row_color' => 'yellow', 'sort_order' => 60,  'countdown_to_ko' => 'KO-4h'],
            ['title' => 'Accreditation Zoning Activation: KO-4',                                                                             'row_color' => 'yellow', 'sort_order' => 70,  'countdown_to_ko' => 'KO-4h'],
            ['title' => 'Media PSA Operational',                                                                                              'row_color' => 'red',    'sort_order' => 80,  'countdown_to_ko' => null],
            ['title' => '30M to GO - Ensure operational readiness: KO -3h30',                                                                 'row_color' => 'yellow', 'sort_order' => 90,  'countdown_to_ko' => 'KO-3h30m'],
            ['title' => 'FULL Floodlights ON',                                                                                                'row_color' => 'yellow', 'sort_order' => 100, 'countdown_to_ko' => null],
            ['title' => 'GATES OPEN',                                                                                                         'row_color' => 'yellow', 'sort_order' => 110, 'countdown_to_ko' => null],
            ['title' => 'Fan Zone is Operational',                                                                                            'row_color' => 'yellow', 'sort_order' => 120, 'countdown_to_ko' => null],
            ['title' => 'TEAM A KIT VAN ARRIVAL',                                                                                             'row_color' => 'green',  'sort_order' => 130, 'countdown_to_ko' => null],
            ['title' => 'TEAM B KIT VAN ARRIVAL',                                                                                             'row_color' => 'green',  'sort_order' => 140, 'countdown_to_ko' => null],
            ['title' => 'TEAM A ARRIVAL',                                                                                                     'row_color' => 'green',  'sort_order' => 150, 'countdown_to_ko' => null],
            ['title' => 'TEAM B ARRIVAL',                                                                                                     'row_color' => 'green',  'sort_order' => 160, 'countdown_to_ko' => null],
            ['title' => 'Fan Zone is closed',                                                                                                 'row_color' => 'yellow', 'sort_order' => 170, 'countdown_to_ko' => null],
            ['title' => 'Warm Up starts',                                                                                                     'row_color' => 'green',  'sort_order' => 180, 'countdown_to_ko' => null],
            ['title' => 'Warm Up Finishes',                                                                                                   'row_color' => 'green',  'sort_order' => 190, 'countdown_to_ko' => null],
            ['title' => 'Pre-match ceremony starts',                                                                                          'row_color' => 'yellow', 'sort_order' => 200, 'countdown_to_ko' => null],
            ['title' => 'KICK-OFF :KO',                                                                                                       'row_color' => 'green',  'sort_order' => 210, 'countdown_to_ko' => 'KO'],
            ['title' => 'END OF FIRST HALF',                                                                                                  'row_color' => 'green',  'sort_order' => 220, 'countdown_to_ko' => 'KO+45m'],
            ['title' => 'START OF SECOND HALF: FE- 45',                                                                                      'row_color' => 'green',  'sort_order' => 230, 'countdown_to_ko' => 'KO+60m'],
            ['title' => 'All Parkings ready for Egress Operation',                                                                            'row_color' => 'yellow', 'sort_order' => 240, 'countdown_to_ko' => null],
            ['title' => 'STC & TCP closes',                                                                                                   'row_color' => 'yellow', 'sort_order' => 245, 'countdown_to_ko' => null],
            ['title' => 'Official Match Attendance announcement',                                                                             'row_color' => 'yellow', 'sort_order' => 250, 'countdown_to_ko' => null],
            ['title' => 'Redeployment + Egress postmatch',                                                                                    'row_color' => 'yellow', 'sort_order' => 260, 'countdown_to_ko' => null],
            ['title' => 'Egress gates are pre-open : FW-30',                                                                                  'row_color' => 'yellow', 'sort_order' => 265, 'countdown_to_ko' => null],
            ['title' => 'Egress Gates open : FW - 15',                                                                                        'row_color' => 'yellow', 'sort_order' => 270, 'countdown_to_ko' => null],
            ['title' => 'Final Whistle - FW',                                                                                                 'row_color' => 'green',  'sort_order' => 280, 'countdown_to_ko' => 'KO+90m'],
            ['title' => 'Fan Zone is Operational',                                                                                            'row_color' => 'yellow', 'sort_order' => 285, 'countdown_to_ko' => null],
            ['title' => 'Fan Zone is closed',                                                                                                 'row_color' => 'yellow', 'sort_order' => 290, 'countdown_to_ko' => null],
            ['title' => 'Post match Press Conference',                                                                                        'row_color' => 'yellow', 'sort_order' => 300, 'countdown_to_ko' => null],
            ['title' => 'TEAM A has left the stadium',                                                                                        'row_color' => 'green',  'sort_order' => 310, 'countdown_to_ko' => null],
            ['title' => 'Team B has left the stadium',                                                                                        'row_color' => 'green',  'sort_order' => 320, 'countdown_to_ko' => null],
            ['title' => 'Referees have left the stadium',                                                                                     'row_color' => 'green',  'sort_order' => 330, 'countdown_to_ko' => null],
            ['title' => 'Accreditation zoning deactivation',                                                                                  'row_color' => 'yellow', 'sort_order' => 340, 'countdown_to_ko' => null],
            ['title' => 'VOC close/End of Operations',                                                                                        'row_color' => 'yellow', 'sort_order' => 350, 'countdown_to_ko' => null],
        ];

        // Filter items by the FA's colour group
        $colorMap = [
            'CMP' => ['green'],
            'SSI' => ['red'],
            'VUM' => ['yellow'],
        ];

        $colors = $colorMap[$faCode] ?? null;
        $items  = $colors
            ? array_values(array_filter($allItems, fn($i) => in_array($i['row_color'], $colors)))
            : $allItems;

        $now  = now();
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
