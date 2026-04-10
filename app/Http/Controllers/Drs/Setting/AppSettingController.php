<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
{
    //
    public function index()
    {
        $settings = Setting::all();
        return view('drs.setting.application.list', compact('settings'));
    }

    public function get($id)
    {
        $op = Setting::findOrFail($id);
        return response()->json(['op' => $op]);
    }

    public function update(Request $request)
    {
        $rules = [
            'key' => 'required',
            'value' => 'required',
        ];

        $user_id = Auth::user()->id;
        $op = Setting::findOrFail($request->id);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            // $message = 'Employee not create.' . $op->id;
            $message = implode($validator->errors()->all('<div>:message</div>'));
        } else {
            $error = false;
            $message = 'Application Setting ' . $op->key . ' successfully updated';
            $op->key = $request->key;
            $op->value = $request->value;
            $op->save();

            Cache::forget('app_settings');
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
        ]);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $ops = Setting::orderBy($sort, $order);

        if ($search) {
            $ops = $ops->where(function ($query) use ($search) {
                $query->where('key', 'like', '%' . $search . '%')
                    ->orWhere('value', 'like', '%' . $search . '%');
            });
        }
        $total = $ops->count();
        $limit = request("limit");
        $limit = max(1, min($limit, 100)); // min=1, max=100
        $ops = $ops->paginate($limit)->through(function ($op) {
            $div_action = '<div class="font-sans-serif btn-reveal-trigger position-static">';
            $update_action =
                '<a href="javascript:void(0)" class="btn btn-sm" id="editApplicationSetting" data-id=' . $op->id .
                ' data-table="application_table" data-bs-toggle="tooltip" data-bs-placement="right" title="Update">' .
                '<i class="fa-solid fa-pen-to-square text-primary"></i></a>';
            $delete_action =
                '<a href="javascript:void(0)" class="btn btn-sm" data-table="application_table" data-id="' .
                $op->id .
                '" id="deleteRsp" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="fa-solid fa-trash text-danger"></i></a></div></div>';


            $actions = $div_action . $update_action . $delete_action;


            return  [
                'id' => $op->id,
                // 'id' => '<div class="align-middle white-space-wrap fw-bold fs-10 ps-2">' .$op->id. '</div>',
                'key' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->key . '</div>',
                'value' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $op->value . '</div>',
                'actions' => $actions,
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
        //
        // dd($request);
        $user_id = Auth::user()->id;
        $op = new Setting();

        $rules = [
            'key' => 'required',
            'value' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            appLog('validator: ' . $validator->errors());;
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));
        } else {

            $error = false;
            $message = 'Application Setting created succesfully.' . $op->id;

            $op->key = $request->key;
            $op->value = $request->value;
            $op->created_at = $user_id;
            $op->updated_at = $user_id;
            $op->save();

            Cache::forget('app_settings');
        }

        $notification = array(
            'message'       => 'Application Setting created successfully',
            'alert-type'    => 'success'
        );

        return response()->json(['error' => $error, 'message' => $message]);
    }

    public function delete($id)
    {
        $op = Setting::findOrFail($id);
        $op->delete();

        $error = false;
        $message = 'Application Setting deleted successfully.';

        $notification = array(
            'message'       => 'Application Setting deleted successfully',
            'alert-type'    => 'success'
        );

        return response()->json(['error' => $error, 'message' => $message]);
    } // delete

}
