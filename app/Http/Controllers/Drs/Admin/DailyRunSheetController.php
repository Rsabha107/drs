<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drs\DailyRunSheet;
use App\Models\Drs\DailyRunSheetItem;
use App\Models\Drs\Event;
use Illuminate\Http\Request;

class DailyRunSheetController extends Controller
{
    public function index()
    {
        $event = Event::findOrFail(session()->get('EVENT_ID'));
        return view('drs.drs.list', compact('event'));
    }

    public function list(Request $request)
    {
        // Delegate to shared controller
        return app(\App\Http\Controllers\Drs\Shared\DailyRunSheetController::class)->list($request);
    }

    public function show()
    {
        $sheet = DailyRunSheet::with(['event', 'venue', 'match', 'items'])->where('event_id', session()->get('EVENT_ID'))->get();
        return view('drs.admin.report.admin-show', compact('sheet'));
    }


    public function showAdminList(Request $request)
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
