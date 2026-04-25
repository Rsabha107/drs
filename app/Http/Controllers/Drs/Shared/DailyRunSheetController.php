<?php

namespace App\Http\Controllers\Drs\Shared;

use App\Exports\DailyRunSheetExport;
use App\Http\Controllers\Controller;
use App\Models\Drs\DailyRunSheet;
use App\Models\Drs\DailyRunSheetItem;
use App\Models\Drs\Event;
use App\Models\Drs\EventMatch;
use App\Models\Drs\FunctionalArea;
use App\Models\Drs\MdTemplateItem;
use App\Models\Drs\SheetType;
use App\Models\Drs\Venue;
use Carbon\Carbon;
use FontLib\Table\Type\loca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Sheet types available to this user
        if ($user->hasRole('Customer')) {
            $sheetTypes = SheetType::forCustomer($event->id);
        } else {
            $sheetTypes = SheetType::forAdmin($event->id);
        }

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
            $query = DailyRunSheet::with(['venue', 'match', 'functionalArea', 'sheetType'])
                ->where('event_id', $eventId)
                ->whereHas('functionalArea', function ($q) use ($user) {
                    $q->whereIn('id', $user->fa->pluck('id'));
                });
        } elseif ($user->hasRole('SuperAdmin')) {
            // SuperAdmin sees all sheets for the event
            $query = DailyRunSheet::with(['venue', 'match', 'functionalArea', 'sheetType'])
                ->where('event_id', $eventId);
        } else {
            // default to no sheets
            $query = DailyRunSheet::whereRaw('0=1');
        }


        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->venue_id);
        }
        if ($request->filled('sheet_type')) {
            $query->where('sheet_type_id', $request->sheet_type);
        }
        if ($request->filled('functional_area_id')) {
            $query->where('functional_area_id', $request->functional_area_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('sheetType', fn($q2) => $q2->where('code', 'like', "%{$s}%")->orWhere('title', 'like', "%{$s}%"))
                    ->orWhere('run_date', 'like', "%{$s}%")
                    ->orWhereHas('venue', fn($q2) => $q2->where('short_name', 'like', "%{$s}%"));
            });
        }

        $total = $query->count();
        $rows  = $query->orderBy($sort, $order)->paginate($limit)->through(function ($s) {
            // Calculate MD-x date based on sheet_type code and match_date
            $mdDate = '-';
            $sheetTypeCode = $s->sheetType?->code ?? 'N/A';
            if ($s->match && $s->match->match_date) {
                // Extract the number from sheet type (MD-3, MD-2, MD-1, MD)
                $daysOffset = 0;
                if (preg_match('/MD-?(\d+)/', $sheetTypeCode, $matches)) {
                    $daysOffset = (int)$matches[1];
                    // For MD-3, MD-2, MD-1: subtract days from match date
                    $calcDate = \Carbon\Carbon::parse($s->match->match_date)->subDays($daysOffset);
                    $mdDate = $calcDate->format('d/m/Y');
                } elseif ($sheetTypeCode === 'MD') {
                    // MD is the match date itself
                    $mdDate = \Carbon\Carbon::parse($s->match->match_date)->format('d/m/Y');
                }
            }

            return [
                'id'               => $s->id,
                'sheet_type_id'    => $s->sheet_type_id,
                'sheet_type'       => '<span class="badge bg-primary">' . e($mdDate . ' - ' . $sheetTypeCode) . '</span>',
                'venue'            => '<span class="fs-9">' . e($s->venue?->short_name ?? '-') . '</span>',
                'match'            => '<span class="fs-9">' . e($s->match ? $s->match->match_number : '-') . '</span>',
                'teams'            => '<span class="fs-9">' . e($s->match ? $s->match->pma1 . ' vs ' . $s->match->pma2 : '-') . '</span>',
                'functional_area'  => '<span class="fs-9">' . e($s->functionalArea?->title ?? '-') . '</span>',
                'run_date'         => '<span class="fs-9">' . e($s->run_date_dmy) . '</span>',
                'match_date'       => '<span class="fs-9">' . e($s->match && $s->match->match_date ? \Carbon\Carbon::parse($s->match->match_date)->format('d/m/Y') : '-') . '</span>',
                'md_date'          => '<span class="fs-9">' . $mdDate . '</span>',
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
            'sheet_type'         => 'required|integer|exists:sheet_types,id',
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
        if ($user->hasRole('Customer')) {
            return response()->json([
                'error'   => true,
                'message' => 'Only administrators can create Daily Run Sheets.',
            ], 403);
        }

        $eventId          = session()->get('EVENT_ID');
        $matchId          = $request->match_id ?: null;
        $functionalAreaId = $request->functional_area_id ?: null;
        $sheetTypeId      = $request->sheet_type;

        // Load the sheet type to check if it's MD
        $sheetType = SheetType::findOrFail($sheetTypeId);

        // MD with no FA: auto-create three sheets (CMP, SSI, VUM)
        if ($sheetType->code === 'MD' && !$functionalAreaId) {
            return $this->createMdTriple($request, $eventId, $matchId, $sheetTypeId);
        }


        $duplicate = DailyRunSheet::where('event_id', $eventId)
            ->where('venue_id', $request->venue_id)
            ->where('sheet_type_id', $sheetTypeId)
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
            'sheet_type_id'      => $sheetTypeId,
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
        $eventId = session()->get('EVENT_ID');
        
        // Try to find a sheet by ID first, otherwise treat it as sheet_type_id
        $sheet = DailyRunSheet::where('event_id', $eventId)
            ->with(['event', 'venue', 'match', 'functionalArea', 'items'])
            ->find($id);

            // dd($sheet);

        if (!$sheet) {
            // If not found by ID, assume it's a sheet_type_id and get the first sheet with that type
            $sheet = DailyRunSheet::where('event_id', $eventId)
                ->where('sheet_type_id', $id)
                ->with(['event', 'venue', 'match', 'functionalArea', 'items'])
                ->firstOrFail();
            $isSheetTypeView = true;
            $sheetTypeId = $id;
        } else {
            $isSheetTypeView = false;
            $sheetTypeId = null;
        }

        // Log::info('Viewing Daily Run Sheet', ['sheet_id' => $id, 'is_sheet_type_view' => $isSheetTypeView]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('Customer')) {
            // Customers can view any sheet, but can only add/edit items on sheets from their own FA
            $userFaIds = $user->fa()->pluck('functional_areas.id');
            // Log::info('Customer FA IDs', ['user_id' => $user->id, 'fa_ids' => $userFaIds->toArray()]);
            $canEdit = $userFaIds->contains($sheet->functional_area_id);
        } else {
            $canEdit = $user->hasRole('SuperAdmin');
        }

        return view('drs.drs.show', compact('sheet', 'canEdit', 'sheetTypeId', 'isSheetTypeView'));
    }

    public function showList(Request $request, $id)
    {
        $eventId = session()->get('EVENT_ID');

        // Check if we're viewing by sheet_type_id or regular sheet_id
        $isSheetTypeView = $request->input('is_sheet_type_view') === 'true';
        
        if ($isSheetTypeView) {
            // Get all sheets with this sheet_type_id
            $sheetIds = DailyRunSheet::where('event_id', $eventId)
                ->where('sheet_type_id', $id)
                ->pluck('id')
                ->toArray();
            
            if (empty($sheetIds)) {
                return response()->json([
                    'total' => 0,
                    'rows'  => [],
                ]);
            }
        } else {
            $sheet = DailyRunSheet::where('id', $id)
                ->where('event_id', $eventId)
                ->firstOrFail();

            /** @var \App\Models\User $authUser */
            $authUser = Auth::user();

            if ($authUser->hasRole('Customer')) {
                // Customers see all sibling sheets (same event/venue/match/type)
                // can_edit controls who may action each item
                $sheetIds = DailyRunSheet::where('event_id', $eventId)
                    ->where('venue_id', $sheet->venue_id)
                    ->where('sheet_type_id', $sheet->sheet_type_id)
                    ->when($sheet->match_id, fn($q) => $q->where('match_id', $sheet->match_id))
                    ->pluck('id')
                    ->toArray();
            } else {
                // Admins see only the specific sheet
                $sheetIds = [$id];
            }
        }

        /** @var \App\Models\User $authUser */
        $authUser  = Auth::user();
        $userFaIds = $authUser->hasRole('Customer')
            ? $authUser->fa()->pluck('functional_areas.id')->toArray()
            : [];

        $sort  = $request->input('sort', 'start_time');
        $order = $request->input('order', 'asc');
        $limit = max(1, min((int) $request->input('limit', 50), 200));

        $allowedSorts = ['id', 'title', 'start_time', 'end_time', 'countdown_to_ko', 'location'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'start_time';
        }

        // Show items from all sheets in sheetIds
        $query = DailyRunSheetItem::with('runSheet.functionalArea')
            ->whereIn('run_sheet_id', $sheetIds);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $total = $query->count();

        $nullableColumns = ['start_time', 'end_time', 'countdown_to_ko', 'location'];
        if (in_array($sort, $nullableColumns)) {
            $query->orderByRaw("ISNULL(`{$sort}`) ASC, `{$sort}` {$order}");
        } else {
            $query->orderBy($sort, $order);
        }

        $transform = function ($item) use ($authUser, $userFaIds) {
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
        };

        $rows = $query->paginate($limit)->through($transform);

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
            'sheet_type'         => $sheet->sheet_type_id,
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
            'sheet_type'         => 'required|integer|exists:sheet_types,id',
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
        if ($user->hasRole('Customer')) {
            return response()->json([
                'error'   => true,
                'message' => 'Only administrators can edit Daily Run Sheets.',
            ], 403);
        }

        $matchId          = $request->match_id ?: null;
        $functionalAreaId = $request->functional_area_id ?: null;
        $sheetTypeId      = $request->sheet_type;

        $duplicate = DailyRunSheet::where('event_id', session()->get('EVENT_ID'))
            ->where('venue_id', $request->venue_id)
            ->where('sheet_type_id', $sheetTypeId)
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
            'sheet_type_id'      => $sheetTypeId,
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('Customer')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Only administrators can delete Daily Run Sheets.',
                ], 403);
            }
            abort(403, 'Only administrators can delete Daily Run Sheets.');
        }

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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sheets = DailyRunSheet::with(['venue', 'functionalArea', 'sheetType'])
            ->where('event_id', $eventId)
            ->where('id', '!=', $id)
            ->when($user->hasRole('Customer'), function ($q) use ($user) {
                $faIds = $user->fa()->pluck('functional_areas.id');
                $q->whereIn('functional_area_id', $faIds);
            })
            ->orderBy('sheet_type_id')
            ->get()
            ->map(fn($s) => [
                'id'             => $s->id,
                'label'          => implode(' · ', array_filter([
                    $s->sheetType?->code ?? 'N/A',
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


        // Log::info('Copying items from Daily Run Sheet', ['source_id' => $source->id, 'target_id' => $target->id, 'item_count' => $source->items->count()]);
        // Log::debug('Source sheet details', ['source' => $source->toArray()]);

        // $faLabel = $target->functionalArea
        //     ? $target->functionalArea->fa_code . ' — ' . $target->functionalArea->title
        //     : null;

        // Log::debug('Functional area label for copied items', ['fa_label' => $faLabel]);
        // Log::debug('First item before mapping', ['first_item' => $source->items->first() ? $source->items->first()->toArray() : null]);

        $now  = now();
        $rows = $source->items->map(fn($item) => [
            'run_sheet_id'    => $target->id,
            'title'           => $item->title,
            'start_time'      => $item->start_time,
            'end_time'        => $item->end_time,
            'countdown_to_ko' => $item->countdown_to_ko,
            'functional_area' => $item->functional_area,
            'location'        => $item->location,
            'description'     => $item->description,
            'row_color'       => $item->row_color,
            'sort_order'      => $item->sort_order,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->toArray();

        // Log::debug('Mapped items ready for insertion', ['rows' => $rows]);

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

    private function createMdTriple(Request $request, string $eventId, ?int $matchId, int $sheetTypeId): \Illuminate\Http\JsonResponse
    {
        $faCodes = ['CMP', 'SEC', 'VUM'];
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
                ->where('sheet_type_id', $sheetTypeId)
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
                'sheet_type_id'      => $sheetTypeId,
                'run_date'           => $request->run_date,
                'gates_opening'      => $request->gates_opening ?: null,
                'kick_off'           => $request->kick_off ?: null,
                'created_by'         => Auth::id(),
            ]);

            $this->populateMdTemplate($sheet->id, $code, $request->kick_off ?: null);
        }

        return response()->json([
            'error'    => false,
            'message'  => '3 MD Run Sheets created (CMP, SSI, VUM).',
            'redirect' => route('drs.drs.index'),
        ]);
    }

    // ── MD Template ─────────────────────────────────────────────────────────

    /**
     * Populate an MD run sheet with template items from the database.
     * Items are filtered by functional area code.
     *
     * @param int $runSheetId
     * @param string $faCode FA code (CMP, SEC, VUM)
     * @param string|null $kickOff Kick-off time for calculating start times
     */
    private function populateMdTemplate(int $runSheetId, string $faCode = 'ALL', ?string $kickOff = null): void
    {
        // Fetch template items for this functional area from the database
        $templateItems = MdTemplateItem::where('fa_code', $faCode)
            ->orderBy('sort_order')
            ->get();

        if ($templateItems->isEmpty()) {
            return; // No template items found
        }

        $now = now();
        $rows = $templateItems->map(fn($item) => [
            'run_sheet_id'    => $runSheetId,
            'title'           => $item->title,
            'start_time'      => $this->parseCountdownToTime($item->countdown_to_ko, $kickOff),
            'countdown_to_ko' => $item->countdown_to_ko,
            'functional_area' => null, // Will be set from the sheet's functional area
            'location'        => $item->location,
            'description'     => null,
            'row_color'       => $item->row_color,
            'sort_order'      => $item->sort_order,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->toArray();

        DailyRunSheetItem::insert($rows);
    }

    /**
     * Parse a countdown string relative to kick-off time.
     * Examples: 'KO-19h', 'KO-6h30m', 'HT', 'FW+2h', etc.
     *
     * @param string|null $countdown The countdown expression
     * @param string|null $kickOff The kick-off time
     * @return string|null The calculated time in H:i format, or null if cannot be calculated
     */
    private function parseCountdownToTime(?string $countdown, ?string $kickOff): ?string
    {
        if (!$countdown || !$kickOff) {
            return null;
        }

        try {
            $ko = Carbon::parse($kickOff);
        } catch (\Exception) {
            return null;
        }

        $countdown = trim($countdown);

        if ($countdown === 'KO') {
            return $ko->format('H:i');
        }

        if ($countdown === 'HT') {
            return $ko->copy()->addMinutes(45)->format('H:i');
        }

        if ($countdown === 'FW') {
            return $ko->copy()->addMinutes(90)->format('H:i');
        }

        if (preg_match('/^(KO|FW)([+-])(\d+h)?(\d+m)?$/', $countdown, $m)) {
            $base    = $m[1] === 'FW' ? $ko->copy()->addMinutes(90) : $ko->copy();
            $sign    = $m[2] === '+' ? 1 : -1;
            $hours   = !empty($m[3]) ? (int) $m[3] : 0;
            $minutes = !empty($m[4]) ? (int) $m[4] : 0;

            return $base->addMinutes(($hours * 60 + $minutes) * $sign)->format('H:i');
        }

        return null;
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

    public function matchesBySheetType(Request $request)
    {
        $eventId = session()->get('EVENT_ID');
        $sheetTypeId = $request->input('sheet_type_id');
        $venueId = $request->input('venue_id');

        if (!$sheetTypeId || !$venueId) {
            return response()->json([]);
        }

        $sheetType = SheetType::findOrFail($sheetTypeId);

        // If the sheet type is tied to a specific match, return only that match
        if ($sheetType->match_id) {
            $matches = EventMatch::where('id', $sheetType->match_id)
                ->get(['id', 'match_number', 'match_date', 'pma1', 'pma2', 'gates_opening', 'kick_off']);
        } else {
            // Otherwise, return all matches for this venue in this event
            $matches = EventMatch::where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->orderBy('match_date')
                ->get(['id', 'match_number', 'match_date', 'pma1', 'pma2', 'gates_opening', 'kick_off']);
        }

        return response()->json($matches);
    }

    // ── Sheet Types by Event & Venue ─────────────────────────────────────────

    public function getSheetTypes(Request $request)
    {
        $eventId = session()->get('EVENT_ID');
        $venueId = $request->input('venue_id');

        if (!$venueId) {
            return response()->json(['types' => []]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            // Query all sheet types for this venue (regardless of event, initially)
            if ($user->hasRole('Customer')) {
                $types = SheetType::where('available_to_customer', true)
                    ->where(function ($q) use ($venueId) {
                        $q->whereNull('venue_id')->orWhere('venue_id', $venueId);
                    })
                    ->with('match')
                    ->orderBy('sort_order')
                    ->get();
            } else {
                $types = SheetType::query()
                    ->where(function ($q) use ($venueId) {
                        $q->whereNull('venue_id')->orWhere('venue_id', $venueId);
                    })
                    ->with('match')
                    ->orderBy('sort_order')
                    ->get();
            }

            $formattedTypes = $types->map(function ($t) {
                // Calculate MD-x date based on match and sheet type code
                $dateLabel = $t->title; // fallback to title
                
                if ($t->match && $t->match->match_date) {
                    $daysOffset = 0;
                    if (preg_match('/MD-?(\d+)/', $t->code, $matches)) {
                        $daysOffset = (int)$matches[1];
                        $calcDate = \Carbon\Carbon::parse($t->match->match_date)->subDays($daysOffset);
                        $dateLabel = $calcDate->format('d/m/Y') . ' - ' . $t->code;
                    } elseif ($t->code === 'MD') {
                        $dateLabel = \Carbon\Carbon::parse($t->match->match_date)->format('d/m/Y') . ' - MD';
                    }
                } else {
                    // No match associated, just show code
                    $dateLabel = $t->code;
                }

                return [
                    'id' => $t->id,
                    'code' => $t->code,
                    'title' => $dateLabel,
                ];
            });

            return response()->json(['types' => $formattedTypes]);
        } catch (\Throwable $e) {
            \Log::error('Error loading sheet types: ' . $e->getMessage());
            return response()->json(['types' => []]);
        }
    }

    // ── Export ───────────────────────────────────────────────────────────────

    public function export($id)
    {
        $sheet = DailyRunSheet::with('sheetType')->findOrFail($id);
        $filename = 'DailyRunSheet_' . ($sheet->sheetType?->code ?? 'sheet') . '_' . $sheet->run_date . '.xlsx';

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
