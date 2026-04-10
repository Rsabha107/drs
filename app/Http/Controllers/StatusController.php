<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    //
    public function index()
    {
        return view('tracki.setup.status.list');
    }

    public function get($id)
    {
        $status = Status::findOrFail($id);
        return response()->json(['status' => $status]);
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'color' => ['required']
        ]);

        $status = Status::findOrFail($request->id);

        // dd($status);

        if ($status->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Status updated     .', 'id' => $status->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Status couldn\'t updated.']);
        }
    }

    public function store(Request $request)
    {
        // dd('mainEvent');
        $op = new Status();

        $rules = [
            'title' => 'required',
            'color' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        // dd($validator);

        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            $message = 'Status couldn\'t created id' . $op->id;
        } else {

            $error = false;
            $message = 'Status created .' . $op->id;

            $op->title = $request->title;
            $op->color = $request->color;
            $op->active_flag = "1";
            $op->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    } // store


    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = Status::orderBy($sort, $order);

        if ($search) {
            $status = $status->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $status->count();
        $status = $status
            ->paginate(request("limit"))
            ->through(
                fn ($status) => [
                    'id' => $status->id,
                    'title' => '<div class="align-middle white-space-wrap fw-bold fs-8">' . $status->title . '</div>',
                    'color' => '<span class="badge badge-phoenix badge-phoenix-' . $status->color . '">' . $status->title . '</span>',
                    'created_at' => format_date($status->created_at,  'H:i:s'),
                    'updated_at' => format_date($status->updated_at, 'H:i:s'),
                ]
            );


        return response()->json([
            "rows" => $status->items(),
            "total" => $total,
        ]);
    }

    public function delete($id)
    {
        Status::where('id', '=', $id)->delete();

        $notification = array(
            'message'       => 'File deleted successfully',
            'alert-type'    => 'success'
        );

        // dd($taskId);

        return response()->json([
            'error' => false,
            'message' => 'Status deleted successfully',
        ]);

        // Toastr::success('Has been add successfully :)','Success');
        // return Redirect::route('tracki.task.list', $task->event_id)->with($notification);
        // return redirect()->back()->with($notification);
    } // taskFileDelete
}
