<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\Drs\Event;
use App\Models\Drs\Venue;
// use App\Models\Vapp\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Redirect;

class VenueController extends Controller
{
    //
    public function index()
    {
        $venues = Venue::all();
        $events = Event::all();
        // $locations = Location::all();
        return view('drs.setting.venue.list', [
            'venues' => $venues,
            'events' => $events,
            // 'locations' => $locations,
        ]);
    }

    public function get($id)
    {
        $venue = Venue::findOrFail($id);
        return response()->json(['venue' => $venue]);
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'short_name' => ['required'],
        ]);

        $venue = Venue::findOrFail($request->id);

        // dd($venue);

        if ($venue->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Venue updated successfully.', 'id' => $venue->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Venue couldn\'t updated.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $venue = Venue::orderBy($sort, $order);

        if ($search) {
            $venue = $venue->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('short_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $venue->count();
        $limit = request("limit");
        $limit = max(1, min($limit, 100)); // min=1, max=100
        $venue = $venue->paginate($limit)->through(function ($venue) {

        // $location = Location::find($venue->location_id);

            return  [
                'id' => $venue->id,
                // 'id' => '<div class="align-middle white-space-wrap fw-bold fs-8 ps-2">' .$venue->id. '</div>',
                'short_name' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $venue->short_name . '</div>',
                'title' => '<div class="align-middle white-space-wrap fs-9">' . $venue->title . '</div>',
                'created_at' => format_date($venue->created_at,  'H:i:s'),
                'updated_at' => format_date($venue->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $venue->items(),
            "total" => $total,
        ]);
    }

    public function store(Request $request)
    {
        //
        // dd($request);
        $user_id = Auth::user()->id;
        $venue = new Venue();

        $rules = [
            'title' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            appLog('validator: ' . $validator->errors());;
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));  // use this for json/jquery
        } else {

            $error = false;
            $message = 'Venue created succesfully.' . $venue->id;

            $venue->title = $request->title;
            $venue->short_name = $request->short_name;
            $venue->created_by = $user_id;
            $venue->updated_by = $user_id;
            $venue->active_flag = 1;

            $venue->save();


        }

        $notification = array(
            'message'       => 'Venue created successfully',
            'alert-type'    => 'success'
        );

        return response()->json(['error' => $error, 'message' => $message]);

    }

    public function delete($id)
    {
        $ws = Venue::findOrFail($id);
        $ws->delete();

        $error = false;
        $message = 'Venue deleted succesfully.';

        $notification = array(
            'message'       => 'Venue deleted successfully',
            'alert-type'    => 'success'
        );

        return response()->json(['error' => $error, 'message' => $message]);
        // return redirect()->route('tracki.setup.workspace')->with($notification);
    } // delete

}
