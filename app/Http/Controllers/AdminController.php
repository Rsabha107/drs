<?php

namespace App\Http\Controllers;

use App\Mail\SendForgotPasswordMail;
use App\Models\Cms\Event;
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
// use App\Models\Event;
use App\Models\FunctionalArea;
use App\Models\OrganizationBudget;
use App\Models\Task;
use Beta\Microsoft\Graph\Model\Storage as ModelStorage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Dcblogdev\MsGraph\Facades\MsGraph;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

// use Brian2694\Toastr\Facades\Toastr;


class AdminController extends Controller
{
    //
    // public function adminDashboard(){

    //     return view('admin.index');
    // }  // End method

    public function trackiDashboard()
    {
        // dd('inside trackiDashboard');
        $workspace = session()->get('workspace_id');
        $user_department = auth()->user()->department_assignment_id;
        $user_workspace = auth()->user()->workspace_id;

        // if (session()->has('workspace_id')){
        //     dd('session for workspace: '.session()->get('workspace_id'));
        // }

        $proj_count = Event::leftJoin('tasks', 'tasks.event_id', '=', 'events.id')
            ->whereNull('archived')
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })->distinct('events.id')->count();

        $unbudgeted_proj_count = Event::leftJoin('tasks', 'tasks.event_id', '=', 'events.id')
            ->leftJoin('funds_category', 'funds_category.id', '=', 'events.fund_category_id')
            ->whereNull('archived')
            ->whereNot(function ($query) {
                $query->where('funds_category.name', '=', 'Budgeted');
            })
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })->distinct('events.id')->count();

        $task_count = Task::join('events', 'events.id', '=', 'tasks.event_id')
            ->whereNull('events.archived')
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })
            ->when(auth()->user()->functional_area_id, function ($query, $user_fa) {
                return $query->where('events.functional_area_id', $user_fa);
            })
            ->count();

        $late_tasks_count = Task::join('events', 'events.id', '=', 'tasks.event_id')
            ->whereNull('events.archived')
            ->whereRaw('datediff(tasks.due_date, CURRENT_DATE) < 0')
            ->where('tasks.progress', '<', 1)
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })
            ->when(auth()->user()->functional_area_id, function ($query, $user_fa) {
                return $query->where('events.functional_area_id', $user_fa);
            })
            ->count();

        $ending_tasks_count = Task::join('events', 'events.id', '=', 'tasks.event_id')
            ->whereNull('events.archived')
            ->whereRaw('datediff(tasks.due_date, CURRENT_DATE) < 3')
            ->whereRaw('datediff(tasks.due_date, CURRENT_DATE) >= 0')
            ->where('tasks.progress', '<', 1)
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })
            ->when(auth()->user()->functional_area_id, function ($query, $user_fa) {
                return $query->where('events.functional_area_id', $user_fa);
            })
            ->count();

        $starting_tasks_count = Task::join('events', 'events.id', '=', 'tasks.event_id')
            ->whereNull('events.archived')
            ->whereRaw('datediff(tasks.start_date, CURRENT_DATE) < 3')
            ->whereRaw('datediff(tasks.start_date, CURRENT_DATE) >= 0')
            ->where('tasks.progress', '<', 1)
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })
            ->when(auth()->user()->functional_area_id, function ($query, $user_fa) {
                return $query->where('events.functional_area_id', $user_fa);
            })
            ->count();

        $total_yearly_budget = OrganizationBudget::where('type', 'year')
            ->whereYear('date_from', date('Y'))
            ->first();

        $total_spent_by_department = Task::join('events', 'events.id', '=', 'tasks.event_id')
            ->join('department', 'department.id', '=', 'tasks.department_assignment_id')
            ->whereNull('events.archived')
            ->select('department.name', DB::raw("sum(tasks.actual_budget_allocated) as value"))
            ->whereYear('tasks.start_date', date('Y'))
            ->groupBy('department.name')
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })
            ->when(auth()->user()->functional_area_id, function ($query, $user_fa) {
                return $query->where('events.functional_area_id', $user_fa);
            })
            ->having('value', '>', '0')
            ->get();

        $total_yearly_spent = Task::select(DB::raw("sum(tasks.actual_budget_allocated) as total_spent"))
            ->join('events', 'events.id', '=', 'tasks.event_id')
            ->whereNull('events.archived')
            ->whereYear('tasks.start_date', date('Y'))
            ->first();

        // $completed_projects_by_month = Event::select(DB::raw('count(*) as total, date_format(end_date, "%m") as month'))
        //     ->whereYear('end_date', date('Y'))
        //     ->where('event_status', '=', config('tracki.project_status.completed'))
        //     ->whereNull('archived')
        //     ->groupBy('month')
        //     ->get();

        // DB::enableQueryLog();
        $total_sales_by_month = Event::select(DB::raw('IFNULL(sum(events.total_sales), 0) count, cal.month'))
            ->rightJoin('cal', function ($join) {
                $join
                    ->on('cal.month_num', DB::raw('date_format(end_date, "%m")'))
                    ->whereYear('end_date', date('Y'))
                    ->where('event_status', '=', config('tracki.project_status.completed'))
                    ->whereNull('archived');
            })
            ->groupBy('cal.month')
            ->orderBy('cal.month_num')
            ->get();

        $completed_projects_by_month = Event::select(DB::raw('IFNULL(count(date_format(end_date, "%m")), 0) count, cal.month'))
            ->rightJoin('cal', function ($join) {
                $join
                    ->on('cal.month_num', DB::raw('date_format(end_date, "%m")'))
                    ->whereYear('end_date', date('Y'))
                    ->where('event_status', '=', config('tracki.project_status.completed'))
                    ->whereNull('archived');
            })
            ->groupBy('cal.month')
            ->orderBy('cal.month_num')
            ->get();

        $projects_by_month = DB::table('events')->select(DB::raw('IFNULL(count(date_format(end_date, "%m")), 0) count, cal.month'))
            ->rightJoin('cal', function ($join) {
                $join
                    ->on('cal.month_num', DB::raw('date_format(end_date, "%m")'))
                    ->whereYear('end_date', date('Y'))
                    ->whereNull('archived');
            })
            ->groupBy('cal.month')
            ->orderBy('cal.month_num')
            ->get();

        // dd(DB::getQueryLog());
        // dd($completed_projects_by_month1);

        $budgeted_projects_by_month = Event::select(DB::raw('IFNULL(count(date_format(start_date, "%m")), 0) count, cal.month'))
            ->rightJoin('cal', function ($join) {
                $join
                    ->on('cal.month_num', DB::raw('date_format(start_date, "%m")'))
                    ->whereYear('start_date', date('Y'))
                    // ->where('event_status', '=', config('tracki.project_status.completed'))
                    ->where('fund_category_id', '=', '1')
                    ->whereNull('archived');
            })
            ->groupBy('cal.month')
            ->orderBy('cal.month_num')
            ->get();

        $unbudgeted_projects_by_month = Event::select(DB::raw('IFNULL(count(date_format(start_date, "%m")), 0) count, cal.month'))
            ->rightJoin('cal', function ($join) {
                $join
                    ->on('cal.month_num', DB::raw('date_format(start_date, "%m")'))
                    ->whereYear('start_date', date('Y'))
                    // ->where('event_status', '=', config('tracki.project_status.completed'))
                    ->where('fund_category_id', '=', '2')
                    ->whereNull('archived');
            })
            ->groupBy('cal.month')
            ->orderBy('cal.month_num')
            ->get();

        //  dd($budgeted_projects_by_month);


        // $fund_projects_by_month = Event::selectRaw('count(*) as total')
        //     ->selectRaw('count(case when fund_category_id=1 then 1 end) as budgeted')
        //     ->selectRaw('count(case when fund_category_id=2 then 1 end) as unbudgeted')
        //     ->selectRaw('date_format(end_date, "%m") as month')
        //     ->groupBy('month')
        //     ->whereYear('end_date', date('Y'))
        //     ->where('event_status', '=', config('tracki.project_status.completed'))
        //     ->whereNull('archived')
        //     ->get();


        $budgeted_monthly = array();
        $i = 0;
        foreach ($budgeted_projects_by_month as $cp) {
            $budgeted_monthly[$i] = $cp->count;
            $i++;
        }

        // dd($budgeted_monthly);

        $unbudgeted_monthly = array();
        $i = 0;
        foreach ($unbudgeted_projects_by_month as $cp) {
            $unbudgeted_monthly[$i] = $cp->count;
            $i++;
        }

        $completed_projects_by_month_array = array();
        $i = 0;
        foreach ($completed_projects_by_month as $cp) {
            $completed_projects_by_month_array[$i] = $cp->count;
            $i++;
        }

        $projects_by_month_array = array();
        $i = 0;
        foreach ($projects_by_month as $cp) {
            $projects_by_month_array[$i] = $cp->count;
            $i++;
        }

        $total_sales_by_month_array = array();
        $i = 0;
        foreach ($total_sales_by_month as $cp) {
            $total_sales_by_month_array[$i] = $cp->count;
            $i++;
        }

        if ($total_yearly_budget) {
            $remaining_budget = $total_yearly_budget?->budget_amount - $total_yearly_spent?->total_spent;
            // $total_yearly_budget->budget_amount

            $budget_percentage_used = ($total_yearly_spent?->total_spent / $total_yearly_budget?->budget_amount) * 100;
        } else {
            $remaining_budget = 0;
            $budget_percentage_used = 0;
        }

        $todo_status_chart = Event::join('statuses', 'statuses.id', '=', 'events.event_status')
            ->select('statuses.title as name', DB::raw("count(statuses.title) as value"))
            ->groupBy('statuses.title')
            ->when($workspace, function ($query, $workspace) {
                return $query->where('events.workspace_id', $workspace);
            })
            ->having('value', '>', '0')
            ->get();

        $project_status_chart = Event::join('statuses', 'statuses.id', '=', 'events.event_status')
            ->select('statuses.title as name', DB::raw("count(statuses.title) as value"))
            ->groupBy('statuses.title')
            ->when($workspace, function ($query, $workspace) {
                return $query->where('events.workspace_id', $workspace);
            })
            ->having('value', '>', '0')
            ->get();
        // dump(vsprintf(str_replace(['?'], ['\'%s\''], $total_sales_by_month->toSql()), $total_sales_by_month->getBindings()));

        // dd($total_sales_by_month_array);
        // dd($total_sales_by_month->getBindings());
        // dd($total_sales_by_month->toSql());

        return view('tracki.index', [
            'project_count' => $proj_count,
            'task_count' => $task_count,
            'late_tasks_count' => $late_tasks_count,
            'ending_tasks_count' => $ending_tasks_count,
            'starting_tasks_count' => $starting_tasks_count,
            'total_yearly_budget' => $total_yearly_budget,
            'total_yearly_spent' => $total_yearly_spent,
            'budget_percentage_used' => $budget_percentage_used,
            'unbudgeted_proj_count' => $unbudgeted_proj_count,
            'remaining_budget' => $remaining_budget,
            'total_spent_by_department' => $total_spent_by_department,
            'completed_projects_by_month' => $completed_projects_by_month_array,
            'projects_by_month' => $projects_by_month_array,
            'budgeted_projects_by_month' => $budgeted_monthly,
            'unbudgeted_projects_by_month' => $unbudgeted_monthly,
            'total_sales_by_month' => $total_sales_by_month_array,
            'project_status_chart' => $project_status_chart,
            'todo_status_chart' => $todo_status_chart,
            'user_workspace' => $user_workspace,
        ]);
    }  //trackiDashboard

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/tracki/auth/login');
    } // End method

    public function login()
    {
        Auth::guard('web')->logout();
        return view('tracki.auth.sign-in');
    }

    public function signUp()
    {
        $departments = Department::all();
        $fa = FunctionalArea::all();
        return view('tracki.auth.sign-up', compact(['departments', 'fa']));
    }

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function submitForgetPasswordForm(Request $request): RedirectResponse
    {
        appLog('inside submitForgetPasswordForm');
        $rules = [
            'email' => 'required|email|exists:users',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $token = sha1(time() . config('global.key'));

        $reset_token = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email
            ])
            ->first();
        
        if ($reset_token) {
            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        }
        appLog('token: '.$token);
        try {
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors('A reset password was already sent to your email.  please check your inbox');
            // return $e->getMessage();
        }

        appLog('after insert');

        $content = [
            'token'     => $token,
            'subject'   => 'VAPP: Reset Password Link',
            'url'       => "route('reset.password.get', $token)",
        ];

        Mail::to($request->email)->queue(new SendForgotPasswordMail($content));

        // Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
        //     $message->to($request->email);
        //     $message->subject('Reset Password');
        // });

        return back()->with('message', 'We have e-mailed your password reset link!');
    } //submitForgetPasswordForm

    public function showResetPasswordForm($token): View
    {
        return view('auth.reset', ['token' => $token]);
    } //showResetPasswordForm

    public function resetFirstPassword(Request $request): RedirectResponse
    {
        $rules = [
            'password' => 'required|string|confirmed',
            // 'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $user = User::where('email', $request->email)
            ->where('first_login_token', $request->ft_token)
            ->first();

        if (!$user) {
            appLog('update failed');
            return back()->withInput()->withErrors(['error' => 'Invalid token!']);
        }

        $updatePassword = $user->update([
            'password' => Hash::make($request->password),
            'first_login_token' => null,
            'first_login_flag' => 0
        ]);

        if (!$updatePassword) {
            appLog('update failed');
            return back()->withInput()->withErrors(['error' => 'Invalid token!']);
        }

        return redirect('login')->with('message', 'Your password has been changed!');
    } //resetFirstPassword

    public function submitResetPasswordForm(Request $request): RedirectResponse
    {
        $rules = [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }



        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            appLog('update failed');
            return back()->withInput()->withErrors(['error' => 'Invalid token!']);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
    } //submitResetPasswordForm

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

        // $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->department_assignment_id = $request->department_id;
        $data->password = Hash::make($request->password);
        // $data->department_assignment_id = $request->department_id;
        // $data->functional_area_id = $request->functional_area_id;
        $data->status = 1;
        $data->role = 'admin';
        $data->address = 'doha';


        $data->save();

        $notification = array(
            'message'       => 'User created successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
        return Redirect::route('tracki.auth.signup')->with($notification);
        //mainProfileStore

    }

    public function reportList()
    {
        return view('tracki.report');
    }

    public function orderForm()
    {

        // $country_codes = DB::table('item_category')->orderBy('arabic_value', 'asc')->get();

        $venue_type = VenueType::all();
        // dd($venue_name);
        $item_category = ItemCategory::all();
        $logical_space_categories = LogicalSpaceCategory::all();
        return view('tracki.order-form', [
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

        return view('tracki.profile-view', compact('profileData'));
    }

    public function mainOrderStore(Request $request)
    {
        // Log::debug('*****************mainOrderStore********session exists?? 1 is ok, 0 is not' . session()->has('user_session'));

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

        // Log::debug("totalRecords: " . $totalRecords);

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

        // Log::debug("totalRecordsWithFilter: " . $totalRecordsWithFilter);

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

        // Log::debug("totalRecords: " . $totalRecords);

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

        // Log::debug("totalRecordsWithFilter: " . $totalRecordsWithFilter);

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
