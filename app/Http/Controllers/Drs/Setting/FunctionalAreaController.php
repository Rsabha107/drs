<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\Drs\FunctionalArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FunctionalAreaController extends Controller
{
    public function index()
    {
        $functional_areas = FunctionalArea::all();
        return view('drs.setting.functional_area.list', [
            'functional_areas' => $functional_areas,
        ]);
    }

    public function get($id)
    {
        $functional_area = FunctionalArea::findOrFail($id);
        return response()->json(['functional_area' => $functional_area]);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $functional_area = FunctionalArea::orderBy($sort, $order);

        if ($search) {
            $functional_area = $functional_area->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('fa_code', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $functional_area->count();
        $limit = request("limit");
        $limit = max(1, min($limit, 100));
        $functional_area = $functional_area->paginate($limit)->through(function ($functional_area) {
            return [
                'id' => $functional_area->id,
                'fa_code' => '<div class="align-middle white-space-wrap fs-9 ps-3">' . $functional_area->fa_code . '</div>',
                'title' => '<div class="align-middle white-space-wrap fs-9">' . $functional_area->title . '</div>',
                'focal_point_name' => '<div class="align-middle white-space-wrap fs-9">' . $functional_area->focal_point_name . '</div>',
                'focal_point_email' => '<div class="align-middle white-space-wrap fs-9">' . $functional_area->focal_point_email . '</div>',
                'created_at' => format_date($functional_area->created_at, 'H:i:s'),
                'updated_at' => format_date($functional_area->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $functional_area->items(),
            "total" => $total,
        ]);
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $functional_area = new FunctionalArea();

        $rules = [
            'title' => 'required',
            'fa_code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            appLog('validator: ' . $validator->errors());
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));
        } else {
            $error = false;
            $message = 'Functional Area created successfully.';

            $functional_area->fa_code = $request->fa_code;
            $functional_area->title = $request->title;
            $functional_area->focal_point_name = $request->focal_point_name;
            $functional_area->focal_point_email = $request->focal_point_email;
            $functional_area->created_by = $user_id;
            $functional_area->updated_by = $user_id;
            $functional_area->active_flag = 1;

            $functional_area->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'fa_code' => ['required'],
        ]);

        $functional_area = FunctionalArea::findOrFail($request->id);

        $functional_area->fa_code = $request->fa_code;
        $functional_area->title = $request->title;
        $functional_area->focal_point_name = $request->focal_point_name;
        $functional_area->focal_point_email = $request->focal_point_email;
        $functional_area->updated_by = Auth::user()->id;

        if ($functional_area->save()) {
            return response()->json(['error' => false, 'message' => 'Functional Area updated successfully.', 'id' => $functional_area->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Functional Area couldn\'t be updated.']);
        }
    }

    public function delete($id)
    {
        $functional_area = FunctionalArea::findOrFail($id);
        $functional_area->delete();

        return response()->json(['error' => false, 'message' => 'Functional Area deleted successfully.']);
    }
}
