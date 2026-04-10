<?php

namespace App\Http\Controllers\GeneralSettings;

use App\Http\Controllers\Controller;

use App\Models\AddressType;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeAddress;
use App\Models\EmployeeFile;
use App\Models\EmployeeRelationship;
use App\Models\EmployeeType;
use App\Models\Gender;
use App\Models\GeneralSettings\Company;
use App\Models\GeneralSettings\CompanyAddress;
use App\Models\Language;
use App\Models\MaritalStatus;
use App\Models\Nationality;
use App\Models\Relationship;
use App\Models\Salutation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        $addresses = CompanyAddress::all();


        // dd(FacadesRoute::currentRouteName());
        // dd(FacadesRequest::url());
        return view('general.settings.address.list', compact(
            'countries',
            'addresses',
        ));
    }

    public function list()
    {

        // dd('address controller list');
        $user = User::findOrFail(Auth::user()->id);
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $op = CompanyAddress::orderBy($sort, $order);

        // dd($op);


        if ($search) {
            $op = $op->where(function ($query) use ($search) {
                $query->where('address1', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }
        $total = $op->count();

        //apply scope

        $op = $op->paginate(request("limit"))->through(function ($op) {

            $actions =
                '<div class="font-sans-serif btn-reveal-trigger position-static">' .
                '<a href="javascript:void(0)" class="btn btn-sm" id="edit_company_address" data-action="update" " data-type="edit" data-id=' .
                $op->id .
                ' data-table="business_address_table" data-bs-toggle="tooltip" data-bs-placement="right" title="Update">' .
                '<i class="fa-solid fa-pen-to-square text-primary"></i></a>' .
                '<a href="javascript:void(0)" class="btn btn-sm" data-table="business_address_table" data-id="' .
                $op->id .
                '" id="deleteBusinessAddress" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="bx bx-trash text-danger"></i></a></div></div>';

            return [
                'id1' => '<div class="ms-3">' . $op->id . '</div>',
                'id' => $op->id,
                'address1' => '<div class="ms-3">' . $op->address1 . '</div>',
                'address2' => $op->address2,
                'city' => $op->city,
                'state' => $op->state,
                'zipcode' => $op->zipcode,
                'actions' => $actions,
                'country' => $op->country?->country_name,
                'default_address' => $op->default_address,
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
        //
        $id = Auth::user()->id;
        $op = new CompanyAddress();

        $rules = [
            'location_name' => 'required',
            'address1' => 'required',
            'country_id' => 'required',
            'default_address' => Rule::unique('business_addresses')->where('company_id', $request->id),
        ];

        $customMessages = [
            'default_address.unique' => 'Only one primary address is allowed.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        // dd($validator);


        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));
        } else {
            $error = false;
            $message = 'Business Address created .';


            $op->company_id = Company::first()->id;
            $op->location_name = $request->location_name;
            $op->address1 = $request->address1;
            $op->address2 = $request->address2;
            $op->city = $request->city;
            $op->state = $request->state;
            $op->zipcode = $request->zipcode;
            $op->country_id = $request->country_id;
            $op->default_address = $request->default_address;
            $op->created_by = $id;
            $op->updated_by = $id;

            $op->save();

            // dd($op->number);
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $user_id = Auth::user()->id;
        $op = CompanyAddress::findOrFail($request->id);

        $rules = [
            'address1' => 'required',
            'country_id' => 'required',
            'default_address' => Rule::unique('company_addresses')->where('company_id', $op->company_id)
                ->where('id', '!=', $op->id)
        ];


        $customMessages = [
            'default_address.unique' => 'Only one default address is allowed.'
        ];

        appLog($rules);
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            $message = implode($validator->errors()->all('<div>:message</div>'));
        } else {
            $error = false;
            $message = 'Business Address created .';

            $op->location_name = $request->location_name;
            $op->address1 = $request->address1;
            $op->address2 = $request->address2;
            $op->city = $request->city;
            $op->state = $request->state;
            $op->zipcode = $request->zipcode;
            $op->country_id = $request->country_id;
            $op->default_address = $request->default_address;
            $op->updated_by = $user_id;
            
            $op->save();
        }

        return response()->json([
            'error' => $error,
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        //
        CompanyAddress::where('id', '=', $id)->delete();

        $notification = array(
            'message'       => 'Business address deleted successfully',
            'alert-type'    => 'success'
        );

        // dd($taskId);

        return response()->json([
            'error' => false,
            'message' => 'Business address deleted successfully',
        ]);
    }

    // return the edit compnay address view
    public function getAddressEditView($id)
    {
        $company_address = CompanyAddress::findOrFail($id);
        $countries = Country::all();

        $view = view('/general/settings/address/mv/edit', [
            'company_address' => $company_address,
            'countries' => $countries,
        ])->render();

        return response()->json(['view' => $view]);
    }
}
