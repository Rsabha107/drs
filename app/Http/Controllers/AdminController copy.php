<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCategory;
use App\Models\LogicalSpaceCategory;
use App\Models\LogicalSpaceSubcategory;
use App\Models\LogicalSpaceName;
use App\Models\ItemSubcategory;
use App\Models\Product;
use App\Models\SiteCategory;
use App\Models\Site;
use App\Models\VenueType;
use App\Models\Department;
use App\Models\Event;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;



// use Brian2694\Toastr\Facades\Toastr;


class AdminController extends Controller
{
    //
    // public function adminDashboard(){

    //     return view('admin.index');
    // }  // End method

    public function mainDashboard()
    {
        // dd('inside mainDashboard');
        $projectData = Event::whereNull('archived');
        $taskData = Task::all();
        $proj_count = $projectData->count();
        $task_count = $taskData->count();
        $late_tasks_count = Task::whereRaw('datediff(due_date, CURRENT_DATE) < 0')
                                ->where('progress', '<', 1)
                                ->count();

        return view('main.index', ['project_count' => $proj_count, 'task_count' => $task_count, 'late_tasks_count' => $late_tasks_count]);
    }

    public function mainLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Auth::logoutOtherDevices($request->password);
        
        return redirect('/main/login');
    } // End method

    public function mainLogin()
    {
        return view('main.sign-in');
    }

    public function signUp()
    {
        $departments = Department::all();
        return view('main.sign-up', compact('departments'));
    }

    public function createUser(Request $request)
    {

        $rules = [
            'username' => 'required|unique:users',
            'password' => 'required|confirmed|min:8|max:16',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            //return ($request->get('password').' - '.$request->get('password_confirmation'));
            //return ($request->input());
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $activate_value = sha1(time() . config('global.key'));

        // $id = Auth::user()->id;
        $data = new User;

        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->department_assignment_id = $request->department_id;
        $data->password = Hash::make($request->password);
        $data->status = 'active';
        $data->role = 'admin';
        $data->address = 'doha';


        $data->save();

        $notification = array(
            'message'       => 'User created successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
        return Redirect::route('main.login')->with($notification);
        //mainProfileStore

    }

    public function reportList()
    {
        return view('main.report');
    }

    public function orderForm()
    {

        // $country_codes = DB::table('item_category')->orderBy('arabic_value', 'asc')->get();

        $venue_type = VenueType::all();
        // dd($venue_name);
        $item_category = ItemCategory::all();
        $logical_space_categories = LogicalSpaceCategory::all();
        return view('main.order-form', [
            'item_category'  => $item_category,
            'venue_type'    => $venue_type,
            'logical_space_categories'    => $logical_space_categories,
        ]);
    }

    public function getSiteCategory(Request $request)
    {
        $all_site_categories = SiteCategory::where('venue_type_id', $request->venue_type_id)->get();
        return response()->json([
            'status'   => 'success',
            'all_site_categories' => $all_site_categories,
        ]);
    }

    public function getSiteCode(Request $request)
    {
        $all_site_codes = Site::where('site_category_id', $request->venue_id)->get();
        return response()->json([
            'status'   => 'success',
            'all_site_codes' => $all_site_codes,
        ]);
    }

    public function getSiteData(Request $request)
    {
        $all_site_codes = Site::where('site_id', $request->site_id)->get();
        return response()->json([
            'status'   => 'success',
            'all_site_codes' => $all_site_codes,
        ]);
    }

    public function getLogicalSpaceSubcategory(Request $request)
    {
        $all_ls_subcat = LogicalSpaceSubcategory::where('category_id', $request->category_id)
            ->where('active_flag', '1')
            ->get();
        return response()->json([
            'status'   => 'success',
            'all_ls_subcat' => $all_ls_subcat,
        ]);
    }

    public function getLogicalSpaceName(Request $request)
    {
        $all_ls_name = LogicalSpaceName::where('subcat_id', $request->subcat_id)
            ->where('active_flag', '1')
            ->get();
        return response()->json([
            'status'   => 'success',
            'all_ls_name' => $all_ls_name,
        ]);
    }

    public function getLogicalSpaceCode(Request $request)
    {
        $all_ls_code = LogicalSpaceName::where('logical_space_id', $request->logical_space_id)
            ->where('active_flag', '1')
            ->get();
        return response()->json([
            'status'   => 'success',
            'all_ls_code' => $all_ls_code,
        ]);
    }

    public function getItemSubcategory(Request $request)
    {
        $all_item_subcategory = ItemSubcategory::where('item_category_id', $request->item_category_id)
            ->where('active_flag', '1')
            ->get();
        return response()->json([
            'status'   => 'success',
            'all_item_subcategory' => $all_item_subcategory,
        ]);
    }

    public function getItemName(Request $request)
    {
        $all_item_name = Product::where('item_subcat_id', $request->item_subcat_id)
            ->where('active', '1')
            ->get();
        return response()->json([
            'status'   => 'success',
            'all_item_name' => $all_item_name,
        ]);
    }

    public function userProfile()
    {
        // first get the auth user
        $id = Auth::user()->id;
        $profileData = User::find($id);

        // dd($profileData);

        return view('main.profile-view', compact('profileData'));
    }

    public function mainOrderStore(Request $request)
    {
        Log::debug('*****************mainOrderStore********session exists?? 1 is ok, 0 is not' . session()->has('user_session'));

        $id = Auth::user()->id;

        $rules = [
            'site_type' => 'required|integer',
            // 'site_type' => 'required|alpha_dash|min:3|max:25',
            'site_category' => 'required|integer',
            'site_code' => 'required',
            'site_name' => 'required',
            'logical_space_category' => 'required|alpha_dash',
            'logical_space_subcategory' => 'required',
            'logical_space_name' => 'required',
            'logical_space_code' => 'required',
        ];

        // $validator = Validator::make($request->all(), $rules);



        $order = new Order;
        $order->user_id = $id;
        $order->venue_type_id = $request->site_type;
        $order->site_category_id = $request->site_category;
        $order->site_id = $request->site_code;
        $order->project_id = 102;
        // $order->languages_known = implode(',', $request->languages_known);

        $save = $order->save();

        $notification = array(
            'message'       => 'Order# ' . $order->id . ' created successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return redirect()->back()->with($notification);
        if ($order) {
            return redirect()->back()->with($notification);
        } else {
            return back()->with('fail', 'Something went wrong, try again later');
        }
    }  // mainOrderStore


    public function mainProfileStore(Request $request)
    {

        $id = Auth::user()->id;
        $data = User::find($id);

        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = rand() . date('ymdHis') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message'       => 'Profile updated successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return redirect()->back()->with($notification);
    }  //mainProfileStore

    public function getOrderData(Request $request)
    {
        // dd('getPlannerData');
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); // total number of rows per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        // dd($search_arr);
        appLog($draw.' '.$start.' '.$rowPerPage.' '.$columnIndex_arr.' '.$order_arr.' '.$search_arr);
        // echo $draw.' '.$start.' '.$rowPerPage;

        $columnIndex     = $columnIndex_arr[0]['column']; // Column index

        // log::debug('colunmIndex: '.$columnIndex);

        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        // log::debug('columnName: '.$columnName);

        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        $orderDetails = DB::table('order_h');

        $totalRecords = $orderDetails
            ->join('order_item_h', 'order_h.order_id', '=', 'order_item_h.order_id')
            ->join('product', 'order_item_h.product_id', '=', 'product.product_id')
            ->join('project', 'order_h.project_id', '=', 'project.project_id')
            ->select(
                'order_h.order_id',
                'order_item_h.item_order_status',
                'project.project_name',
                'product.product_name as item_name'
            )->count();

        Log::debug("totalRecords: " . $totalRecords);

        $totalRecordsWithFilter = $orderDetails->where(function ($query) use ($searchValue) {
            $query->join('order_item_h', 'order_h.order_id', '=', 'order_item_h.order_id');
            $query->join('product', 'order_item_h.product_id', '=', 'product.product_id');
            $query->join('project', 'order_h.project_id', '=', 'project.project_id');
            $query->select(
                'order_h.order_id',
                'order_item_h.item_order_status',
                'project.project_name',
                'product.product_name as item_name'
            );
            $query->where('order_h.order_id', 'like', '%' . $searchValue . '%');
            $query->orWhere('item_order_status', 'like', '%' . $searchValue . '%');
            $query->orWhere('project_name', 'like', '%' . $searchValue . '%');
            $query->orWhere('product_name', 'like', '%' . $searchValue . '%');
        })->count();

        Log::debug("totalRecordsWithFilter: " . $totalRecordsWithFilter);

        $records = $orderDetails->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->join('order_item_h', 'order_h.order_id', '=', 'order_item_h.order_id');
                $query->join('product', 'order_item_h.product_id', '=', 'product.product_id');
                $query->join('project', 'order_h.project_id', '=', 'project.project_id');
                $query->select(
                    'order_h.order_id',
                    'order_item_h.item_order_status',
                    'project.project_name',
                    'product.product_name as item_name'
                );
                $query->where('order_h.order_id', 'like', '%' . $searchValue . '%');
                $query->orWhere('item_order_status', 'like', '%' . $searchValue . '%');
                $query->orWhere('project_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('product_name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        // Log::debug("records: ".$records);

        $data_arr = [];
        // $records = $orderDetails;

        foreach ($records as $key => $record) {

            if ($record->item_order_status == '1') {
                $status = '<td><span class="badge badge-phoenix badge-phoenix-success">Approved</span></td>';
            } else {
                $status = '<td><span class="badge badge-phoenix badge-phoenix-warning">Rejected</span></td>';
            }

            $hidden_id = '<td hidden class="user_id">' . $record->order_id . '</td>';

            $modify = '
                <td class="text-end">
                    <div class="actions">
                        <a href="#" class="btn btn-sm bg-danger-light">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a class="btn btn-sm bg-danger-light delete user_id" data-bs-toggle="modal" data-user_id="' . $record->order_id . '" data-bs-target="#plannerDelete">
                        <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </td>
            ';

            $data_arr[] = [
                "order_id"         => $record->order_id,
                "status"        => $status, //$record->item_order_status,
                "project_name"  => $record->project_name,
                "item"          => $record->item_name,
                // "active_flag"       => $status,
                "modify"        => $modify,
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];

        // dd(response()->json($response));
        return response()->json($response);
    }  //getPlannerData

    public function getProjectData(Request $request)
    {
        // dd('getPlannerData');
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); // total number of rows per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        // dd($search_arr);
        appLog($draw.' '.$start.' '.$rowPerPage.' '.$columnIndex_arr.' '.$order_arr.' '.$search_arr);
        // echo $draw.' '.$start.' '.$rowPerPage;


        $columnIndex     = $columnIndex_arr[0]['column']; // Column index

        // log::debug('colunmIndex: '.$columnIndex);

        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        // log::debug('columnName: '.$columnName);

        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        $orderDetails = DB::table('order_h');

        $totalRecords = $orderDetails
            ->join('order_item_h', 'order_h.order_id', '=', 'order_item_h.order_id')
            ->join('product', 'order_item_h.product_id', '=', 'product.product_id')
            ->join('project', 'order_h.project_id', '=', 'project.project_id')
            ->select(
                'order_h.order_id',
                'order_item_h.item_order_status',
                'project.project_name',
                'product.product_name as item_name'
            )->count();

        Log::debug("totalRecords: " . $totalRecords);

        $totalRecordsWithFilter = $orderDetails->where(function ($query) use ($searchValue) {
            $query->join('order_item_h', 'order_h.order_id', '=', 'order_item_h.order_id');
            $query->join('product', 'order_item_h.product_id', '=', 'product.product_id');
            $query->join('project', 'order_h.project_id', '=', 'project.project_id');
            $query->select(
                'order_h.order_id',
                'order_item_h.item_order_status',
                'project.project_name',
                'product.product_name as item_name'
            );
            $query->where('order_h.order_id', 'like', '%' . $searchValue . '%');
            $query->orWhere('item_order_status', 'like', '%' . $searchValue . '%');
            $query->orWhere('project_name', 'like', '%' . $searchValue . '%');
            $query->orWhere('product_name', 'like', '%' . $searchValue . '%');
        })->count();

        Log::debug("totalRecordsWithFilter: " . $totalRecordsWithFilter);

        $records = $orderDetails->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->join('order_item_h', 'order_h.order_id', '=', 'order_item_h.order_id');
                $query->join('product', 'order_item_h.product_id', '=', 'product.product_id');
                $query->join('project', 'order_h.project_id', '=', 'project.project_id');
                $query->select(
                    'order_h.order_id',
                    'order_item_h.item_order_status',
                    'project.project_name',
                    'product.product_name as item_name'
                );
                $query->where('order_h.order_id', 'like', '%' . $searchValue . '%');
                $query->orWhere('item_order_status', 'like', '%' . $searchValue . '%');
                $query->orWhere('project_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('product_name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        // Log::debug("records: ".$records);

        $data_arr = [];
        // $records = $orderDetails;

        foreach ($records as $key => $record) {

            if ($record->item_order_status == '1') {
                $status = '<td><span class="badge badge-phoenix badge-phoenix-success">Approved</span></td>';
            } else {
                $status = '<td><span class="badge badge-phoenix badge-phoenix-warning">Rejected</span></td>';
            }

            $hidden_id = '<td hidden class="user_id">' . $record->order_id . '</td>';

            $modify = '
                <td class="text-end">
                    <div class="actions">
                        <a href="#" class="btn btn-sm bg-danger-light">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a class="btn btn-sm bg-danger-light delete user_id" data-bs-toggle="modal" data-user_id="' . $record->order_id . '" data-bs-target="#plannerDelete">
                        <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </td>
            ';

            $data_arr[] = [
                "order_id"         => $record->order_id,
                "status"        => $status, //$record->item_order_status,
                "project_name"  => $record->project_name,
                "item"          => $record->item_name,
                // "active_flag"       => $status,
                "modify"        => $modify,
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];

        // dd(response()->json($response));
        return response()->json($response);
    }  //getPlannerData

}  // end of class
