<?php

namespace App\Http\Controllers\GeneralSettings;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('general.settings.currency.list');
    }


    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $op = Currency::orderBy($sort, $order);

        // dd($op);
        if ($search) {
            $op = $op->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $total = $op->count();

        $op = $op->paginate(request("limit"))->through(function ($op) {

            $actions =
            '<div class="font-sans-serif btn-reveal-trigger position-static">' .
            '<a href="javascript:void(0)" class="btn btn-sm" id="edit_currency" data-action="update" " data-type="edit" data-id=' .
            $op->id .
            ' data-table="currency_table" data-bs-toggle="tooltip" data-bs-placement="right" title="Update">' .
            '<i class="fa-solid fa-pen-to-square text-primary"></i></a>' .
            '<a href="javascript:void(0)" class="btn btn-sm" data-table="currency_table" data-id="' .
            $op->id .
            '" id="delete_currency" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
            '<i class="bx bx-trash text-danger"></i></a></div></div>';

            return [
                'id' => $op->id,
                'id1' => '<div class="ms-3">' . $op->id . '</div>',
                'name' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . $op->name . '</div>',
                'code' => '<div class="align-middle white-space-wrap fw-bold fs-8">' . $op->code . '</div>',
                'symbol' => '<div class="align-middle white-space-wrap fw-bold fs-8">' . $op->symbol . '</div>',
                'actions' => $actions,
                'created_at' => format_date($op->created_at,  'H:i:s'),
                'updated_at' => format_date($op->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $op->items(),
            "total" => $total,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd('mainEvent');
        $user_id = Auth::user()->id;
        $op = new Currency();

        $rules = [
            'name' => 'required',
            'code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        // dd($validator);

        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            // $message = 'EmployeeEntity could not be created';
            $message = implode($validator->errors()->all());
        } else {

            $error = false;
            $message = 'Currency created.';

            $op->name = $request->name;
            $op->code = $request->code;
            $op->symbol = $request->symbol;
            $op->created_by = $user_id;
            $op->updated_by = $user_id;
            $op->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    } // store


    public function get($id)
    {
        $op = Currency::findOrFail($id);
        return response()->json(['op' => $op]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd('mainEvent');
        $user_id = Auth::user()->id;
        $op = Currency::find($request->id);

        $rules = [
            'name' => 'required',
            'code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        // dd($validator);

        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            // $message = 'EmployeeEntity could not be created';
            $message = implode($validator->errors()->all());
        } else {

            $error = false;
            $message = 'Currency updated.';

            $op->name = $request->name;
            $op->code = $request->code;
            $op->symbol = $request->symbol;
            $op->updated_by = $user_id;
            $op->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    } // store

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        //
        Currency::where('id', '=', $id)->delete();

        $notification = array(
            'message'       => 'Currency deleted successfully',
            'alert-type'    => 'success'
        );

        // dd($taskId);

        return response()->json([
            'error' => false,
            'message' => 'Currency deleted successfully',
        ]);
    }
}
