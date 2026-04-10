<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\Drs\Event;
use App\Models\Drs\EventMatch;
use App\Models\Drs\Venue;
use Carbon\Carbon;
// use App\Models\Vapp\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Redirect;

class MatchController extends Controller
{
    //
    public function index()
    {
        $matches = EventMatch::where('event_id', session()->get('EVENT_ID'))->get();
        $venues = Venue::all();
        $event = Event::find(session()->get('EVENT_ID'));

        return view('drs.setting.match.list', [
            'event_matches' => $matches,
            'venues' => $venues,
            'event' => $event,
        ]);
    }

    public function get($id)
    {
        $match = EventMatch::with(['event', 'venue'])->findOrFail($id);
        $match_date = $match->match_date ? Carbon::parse($match->match_date)->format('d/m/Y') : null;
        return response()->json(['match' => $match, 'match_date' => $match_date]);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $ops = EventMatch::with(['event', 'venue'])->orderBy($sort, $order);
        $ops = $ops->where('event_id', session()->get('EVENT_ID'));

        if ($search) {
            $ops = $ops->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('short_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $ops->count();
        $limit = request("limit");
        $limit = max(1, min($limit, 100)); // min=1, max=100
        $ops = $ops->paginate($limit)->through(function ($op) {

            // $location = Location::find($venue->location_id);

            return  [
                'id' => $op->id,
                // 'id' => '<div class="align-middle white-space-wrap fw-bold fs-8 ps-2">' .$venue->id. '</div>',
                'event_name' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->event?->name . '</div>',
                'venue_name' => '<div class="align-middle white-space-wrap fs-9">' . $op->venue?->short_name . '</div>',
                'match_number' => '<div class="align-middle white-space-wrap fs-9">' . $op->match_number . '</div>',
                'stage' => '<div class="align-middle white-space-wrap fs-9">' . $op->stage . '</div>',
                'pma1' => '<div class="align-middle white-space-wrap fs-9">' . $op->pma1 . '</div>',
                'pma2' => '<div class="align-middle white-space-wrap fs-9">' . $op->pma2 . '</div>',
                'match_date' => '<div class="align-middle white-space-wrap fs-9">' . format_date($op->match_date, 'Y-m-d') . '</div>',
                'created_at' => format_date($op->created_at,  'H:i:s'),
                'updated_at' => format_date($op->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $ops->items(),
            "total" => $total,
        ]);
    }

    public function update(Request $request)
    {
        //
        // dd($request);
        $user_id = Auth::user()->id;
        $op = EventMatch::findOrFail($request->id);

        $rules = [
            'venue_id' => 'required',
            'event_id' => 'required',
            'match_number' => 'required',
            'pma1' => 'required',
            'pma2' => 'required',
            'stage' => 'required',
            'match_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            appLog('validator: ' . $validator->errors());;
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));  // use this for json/jquery
        } else {

            $error = false;
            $message = 'Match created succesfully.' . $op->id;

            $op->venue_id = $request->venue_id;
            $op->venue_name = Venue::find($request->venue_id)->title;
            $op->event_id = $request->event_id;
            $op->match_number = $request->match_number;
            $op->pma1 = $request->pma1;
            $op->pma2 = $request->pma2;
            $op->stage = $request->stage;
            $op->match_date = $request->match_date ? Carbon::createFromFormat('d/m/Y', $request->match_date)->toDateString() : null;
            $op->updated_by = $user_id;

            $op->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }

    public function store(Request $request)
    {
        //
        // dd($request);
        $user_id = Auth::user()->id;
        $op = new EventMatch();

        $rules = [
            'venue_id' => 'required',
            'event_id' => 'required',
            'match_number' => 'required',
            'pma1' => 'required',
            'pma2' => 'required',
            'stage' => 'required',
            'match_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            appLog('validator: ' . $validator->errors());;
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));  // use this for json/jquery
        } else {

            $error = false;
            $message = 'Match created succesfully.' . $op->id;

            $op->venue_id = $request->venue_id;
            $op->event_id = $request->event_id;
            $op->venue_name = Venue::find($request->venue_id)->title;
            $op->match_number = $request->match_number;
            $op->pma1 = $request->pma1;
            $op->pma2 = $request->pma2;
            $op->stage = $request->stage;
            $op->match_date = $request->match_date ? Carbon::createFromFormat('d/m/Y', $request->match_date)->toDateString() : null;
            $op->created_by = $user_id;
            $op->updated_by = $user_id;

            $op->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }

    public function delete($id)
    {
        $op = EventMatch::findOrFail($id);
        $op->delete();

        $error = false;
        $message = 'Match deleted succesfully.';

        $notification = array(
            'message'       => 'Match deleted successfully',
            'alert-type'    => 'success'
        );

        return response()->json(['error' => $error, 'message' => $message]);
        // return redirect()->route('tracki.setup.workspace')->with($notification);
    } // delete

}
