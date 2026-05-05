<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\Drs\Event;
use App\Models\Drs\EventMatch;
use App\Models\Drs\SheetType;
use App\Models\Drs\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SheetTypeController extends Controller
{
    //
    public function index()
    {
        $events = Event::all();
        $venues = Venue::all();
        return view('drs.setting.sheet-type.list', [
            'events' => $events,
            'venues' => $venues,
        ]);
    }

    public function get($id)
    {
        $op = SheetType::with(['event', 'venue', 'match'])->findOrFail($id);

        return response()->json(['op' => $op, 'id' => $op->id]);
    }


    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $ops = SheetType::with(['event', 'venue', 'match'])->orderBy($sort, $order);

        if ($search) {
            $ops = $ops->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $ops->count();
        $limit = request("limit");
        $limit = max(1, min($limit, 100)); // min=1, max=100
        $ops = $ops->paginate($limit)->through(function ($op) {

            $div_action = '<div class="font-sans-serif btn-reveal-trigger position-static">';
            $update_action =
                '<a href="javascript:void(0)" class="btn btn-sm" id="editSheetTypes" data-id=' . $op->id .
                ' data-table="sheet_type_table" data-bs-toggle="tooltip" data-bs-placement="right" title="Update">' .
                '<i class="fa-solid fa-pen-to-square text-primary"></i></a>';
            $delete_action =
                '<a href="javascript:void(0)" class="btn btn-sm" data-table="sheet_type_table" data-id="' .
                $op->id .
                '" id="deleteSheetType" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="fa-solid fa-trash text-danger"></i></a></div></div>';

            // Calculate match_date and md_date
            $matchDate = '-';
            $mdDate = '-';
            if ($op->match && $op->match->match_date) {
                $matchDate = \Carbon\Carbon::parse($op->match->match_date)->format('d/m/Y');
                
                // Extract the number from code (MD-3, MD-2, MD-1, MD)
                $daysOffset = 0;
                if (preg_match('/MD-?(\d+)/', $op->code, $matches)) {
                    $daysOffset = (int)$matches[1];
                    // For MD-3, MD-2, MD-1: subtract days from match date
                    $calcDate = \Carbon\Carbon::parse($op->match->match_date)->subDays($daysOffset);
                    $mdDate = $calcDate->format('d/m/Y');
                } elseif ($op->code === 'MD') {
                    // MD is the match date itself
                    $mdDate = $matchDate;
                }
            }

            return  [
                'id' => $op->id,
                'code' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->code . '</div>',
                'title' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->title . '</div>',
                'description' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . ($op->description ?? '-') . '</div>',
                'event' => $op->event ? '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->event->name . '</div>' : '-',
                'venue' => $op->venue ? '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->venue->title . '</div>' : '-',
                'match_number' => $op->match ? '<div class="align-middle white-space-wrap fs-9 ps-3">Match ' . $op->match->match_number . '</div>' : '-',
                'match_date' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $matchDate . '</div>',
                'md_date' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $mdDate . '</div>',
                'cuff_date_time' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . ($op->cuff_date_time ? \Carbon\Carbon::parse($op->cuff_date_time)->format('d/m/Y H:i') : '-') . '</div>',
                'actions' => $update_action . $delete_action,
                'created_at' => format_date($op->created_at,  'H:i:s'),
                'updated_at' => format_date($op->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $ops->items(),
            "total" => $total,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('SheetTypeController::store called');

        $validator = Validator::make($request->all(), [
            'code'              => 'required|string|max:10|unique:sheet_types,code',
            'title'             => 'required|string|max:100',
            'description'       => 'nullable|string',
            'event_id'          => 'required|exists:events,id',
            'venue_id'          => 'required|exists:venues,id',
            'match_id'          => 'nullable|exists:matches,id',
            'cuff_date_time'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => implode('', $validator->errors()->all('<div>:message</div>')),
            ], 422);
        }

        try {
            $op = SheetType::create([
                'code'                   => $request->code,
                'title'                  => $request->title,
                'description'            => $request->description,
                'event_id'               => $request->event_id,
                'venue_id'               => $request->venue_id,
                'match_id'               => $request->match_id,
                'cuff_date_time'         => $request->cuff_date_time,
                'available_to_customer'  => true,
                'sort_order'             => 0,
            ]);

            return response()->json([
                'error'   => false,
                'message' => 'Sheet Type created successfully.',
                'id'      => $op->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('SheetTypeController::store failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'Failed to create sheet type. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        Log::info('SheetTypeController::update called', ['id' => $request->id]);

        $validator = Validator::make($request->all(), [
            'id'                => 'required|exists:sheet_types,id',
            'code'              => 'required|string|max:10|unique:sheet_types,code,' . $request->id,
            'title'             => 'required|string|max:100',
            'description'       => 'nullable|string',
            'event_id'          => 'required|exists:events,id',
            'venue_id'          => 'required|exists:venues,id',
            'match_id'          => 'nullable|exists:matches,id',
            'cuff_date_time'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => implode('', $validator->errors()->all('<div>:message</div>')),
            ], 422);
        }

        try {
            $op = SheetType::findOrFail($request->id);
            
            $op->update([
                'code'              => $request->code,
                'title'             => $request->title,
                'description'       => $request->description,
                'event_id'          => $request->event_id,
                'venue_id'          => $request->venue_id,
                'match_id'          => $request->match_id,
                'cuff_date_time'    => $request->cuff_date_time,
            ]);

            return response()->json([
                'error'   => false,
                'message' => 'Sheet Type updated successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('SheetTypeController::update failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'Failed to update sheet type. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        Log::info('SheetTypeController::delete called', ['id' => $id, 'user_id' => auth()->id()]);

        try {
            $sheetType = SheetType::findOrFail($id);
            $sheetType->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Sheet Type deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('SheetTypeController::delete failed', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'Failed to delete sheet type. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getMatches(Request $request)
    {
        $eventId = $request->input('event_id');
        $venueId = $request->input('venue_id');

        if (!$eventId || !$venueId) {
            return response()->json(['matches' => []]);
        }

        try {
            $matches = EventMatch::where('event_id', $eventId)
                ->where('venue_id', $venueId)
                ->orderBy('match_number')
                ->select('id', 'match_number')
                ->get();

            return response()->json(['matches' => $matches]);
        } catch (\Throwable $e) {
            Log::error('SheetTypeController::getMatches failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['matches' => []]);
        }
    }
}
