<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\Drs\EventDocument;
use App\Models\Drs\ParticipantDocument;
use App\Models\Drs\TempUpload;
use App\Models\Drs\Event;
use App\Models\Drs\SheetType;
use App\Models\Drs\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $op = SheetType::with(['event', 'venue'])->findOrFail($id);

        return response()->json(['op' => $op, 'id' => $op->id]);
        // return response()->json(['op' => $op, 'venues' => $op->venues, 'image_path' => $image_path]);
    }


    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $ops = SheetType::with(['event', 'venue'])->orderBy($sort, $order);

        if ($search) {
            $ops = $ops->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
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


            // $actions = $div_action . $profile_action;
            $venues_display = '';


            return  [
                'id' => $op->id,
                // 'id' => '<div class="align-middle white-space-wrap fw-bold fs-10 ps-2">' .$op->id. '</div>',
                'code' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->code . '</div>',
                'title' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->title . '</div>',
                'description' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->description . '</div>',
                'event' => $op->event ? '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->event->name . '</div>' : '',
                'venue' => $op->venue ? '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->venue->title . '</div>' : '',
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
            'name'              => 'required|string|max:255',
            'event_start_date'  => 'nullable|date',
            'venue_id'          => 'nullable|array',
            'venue_id.*'        => 'exists:venues,id',

            // only for the logo (Dropify)
            'file_name'         => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',

            // FilePond temp ids
            'qid_server_ids'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => implode('', $validator->errors()->all('<div>:message</div>')),
            ], 422);
        }

        $userId   = Auth::id();
        $serverIds = json_decode($request->input('qid_server_ids', '[]'), true) ?: [];

        Log::info('EventController::store payload', [
            'user_id'    => $userId,
            'serverIds'  => $serverIds,
            'name'       => $request->name,
        ]);

        DB::beginTransaction();

        try {
            $op = new SheetType();
            $op->name               = $request->name;
            
            // Convert date format from d/m/Y to Y-m-d
            if ($request->event_start_date) {
                try {
                    $op->event_start_date = Carbon::createFromFormat('d/m/Y', $request->event_start_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $op->event_start_date = $request->event_start_date;
                }
            }
            
            $op->active_flag        = 1;
            $op->created_by         = $userId;
            $op->updated_by         = $userId;

            // =========================
            // Upload logo (Dropify)
            // =========================
            $op->event_logo = 'noimage.jpg';

            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');

                $safeOriginal = preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $fileNameToStore = Str::random(8) . '_' . now()->format('ymdHis') . '_' . $safeOriginal;

                Storage::disk('private')->putFileAs('event/logo', $file, $fileNameToStore);

                $op->event_logo = $fileNameToStore;
            }

            $op->save();

            // =========================
            // Commit FilePond uploads
            // =========================
            if (!empty($serverIds)) {
                $this->commitFilepondUploads($serverIds, $op->id, 'qid');
            }

            // =========================
            // Sync venues (if needed)
            // =========================
            if ($request->venue_id) {
                foreach ($request->venue_id as $key => $data) {
                    $op->venues()->attach($request->venue_id[$key]);
                }
            }

            DB::commit();

            return response()->json([
                'error'   => false,
                'message' => 'Event created successfully.',
                'id'      => $op->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('EventController::store failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'Failed to create event. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $userId = Auth::id();

        // ✅ arrays coming from FilePond + staged deletes
        $serverIds = json_decode($request->input('qid_server_ids', '[]'), true) ?: [];
        $deleteIds = json_decode($request->input('delete_doc_ids', '[]'), true) ?: [];

        Log::info('EventController::update called', [
            'event_id'   => $request->id,
            'serverIds'  => $serverIds,
            'deleteIds'  => $deleteIds,
            'user_id'    => $userId,
        ]);

        // ✅ validate
        $rules = [
            'id'                => 'required|exists:events,id',
            'name'              => 'required|string|max:255',
            'event_start_date'  => 'nullable|date',
            'active_flag'       => 'required|in:1,2',
            'venue_id'          => 'nullable|array',
            'venue_id.*'        => 'exists:venues,id',

            // Only keep this if you really upload event_logo via normal input
            'file_name'         => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',

            // staged deletes field
            'delete_doc_ids'    => 'nullable|string',
            'qid_server_ids'    => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => implode('', $validator->errors()->all('<div>:message</div>')),
            ]);
        }

        DB::beginTransaction();

        try {
            $op = Event::findOrFail($request->id);

            // =========================
            // 1) Update event fields
            // =========================
            $op->name              = $request->name;
            
            // Convert date format from d/m/Y to Y-m-d
            if ($request->event_start_date) {
                try {
                    $op->event_start_date = Carbon::createFromFormat('d/m/Y', $request->event_start_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $op->event_start_date = $request->event_start_date;
                }
            }
            
            $op->active_flag       = $request->active_flag;
            $op->updated_by        = $userId;

            // =========================
            // 2) Update event logo (optional - normal upload, not FilePond)
            // =========================
            // if ($request->hasFile('file_name')) {
            //     $file = $request->file('file_name');

            //     // safer filename
            //     $fileNameToStore = rand() . date('ymdHis') . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            //     // delete old if not default
            //     if (!empty($op->event_logo) && $op->event_logo !== 'default.png' && $op->event_logo !== 'noimage.jpg') {
            //         Storage::disk('private')->delete('event/logo/' . $op->event_logo);
            //     }

            //     Storage::disk('private')->putFileAs('event/logo', $file, $fileNameToStore);
            //     $op->event_logo = $fileNameToStore;
            // }

            $op->save();

            // =========================
            // 3) Commit NEW FilePond uploads (adds DB rows + moves files)
            // =========================
            // NOTE: your existing method should move temp -> final and create doc rows.
            if (!empty($serverIds)) {
                $this->commitFilepondUploads($serverIds, $op->id, 'qid');
            }

            // =========================
            // 4) Delete docs ONLY ON SAVE (staged deletes)
            // =========================
            // Ensure the table has: id, event_id, disk, path (or equivalents)
            if (!empty($deleteIds)) {
                $docs = EventDocument::where('event_id', $op->id)
                    ->whereIn('id', $deleteIds)
                    ->get();

                foreach ($docs as $doc) {
                    Storage::disk($doc->disk ?? 'private')->delete($doc->path);
                    $doc->delete();
                }
            }

            // =========================
            // 5) Sync venues
            // =========================
            if ($op->venues) {
                $op->venues()->detach();
            }
            if ($request->venue_id) {
                foreach ($request->venue_id as $key => $data) {
                    $op->venues()->attach($request->venue_id[$key]);
                }
            }
            // $venueIds = $request->input('venue_id', []);
            // $op->venues()->sync($venueIds);

            DB::commit();

            return response()->json([
                'error'   => false,
                'message' => 'Event updated successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('EventController::update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'Failed to update event. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        Log::info('EventController::delete called', ['id' => $id, 'user_id' => auth()->id()]);

        DB::beginTransaction();

        try {
            $event = Event::findOrFail($id);

            // detach pivot first (clean + avoids FK issues)
            $event->venues()->detach();

            // delete attachments if you have them
            // Example:
            $docs = EventDocument::where('event_id', $event->id)->get();
            foreach ($docs as $doc) {
                Storage::disk($doc->disk ?? 'private')->delete($doc->path);
                $doc->delete();
            }

            $event->delete();

            DB::commit();

            return response()->json([
                'error'   => false,
                'message' => 'Event deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('EventController::delete failed', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'Failed to delete event. ' . $e->getMessage(),
            ], 500);
        }
    }
}
