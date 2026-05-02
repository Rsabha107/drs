<?php

namespace App\Http\Controllers\Drs\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreationMail;
use App\Mail\OtpMail;
use App\Mail\SendForgotPasswordMail;
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
use App\Models\Drs\Event;
use App\Models\Drs\FunctionalArea as DrsFunctionalArea;
use App\Models\Drs\Guardian;
use App\Models\Drs\GuardianDocument;
use App\Models\Drs\TempUpload;
use App\Models\Drs\Venue;
use App\Models\Task;
use App\Models\Vapp\FunctionalArea;
use App\Notifications\EmailOtpVerification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use TechEd\SimplOtp\SimplOtp;
use TechEd\SimplOtp\Models\SimplOtp as OTPModel;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

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

        return redirect('login');
    } // End method

    public function login()
    {
        Auth::guard('web')->logout();
        return view('vapp.auth.sign-in');
    }

    public function verifyOtpAndLoginxx(Request $request)
    {
        // $maxAttempts = (int) config('simple-otp.otp_max_attempts');
        // $otp_attempts = SimpleOTP::where('identity', auth()->user()->email)
        //     ->where('validated_at', null)
        //     ->latest()
        //     ->first();

        // if($otp_attempts->attempts >= $maxAttempts){
        //     $notification = array(
        //         'message' => 'Max attempts reached',
        //         'alert-type' => 'error'
        //     );
        //     return redirect('/tracki/auth/signin')->with($notification);
        //     // return redirect('tracki/auth/otp')->with($notification);
        // };

        $user = auth()->user();

        // $isValid = SimpleOTP::verify(auth()->user()->email, $request->otp);
        $isValid = SimplOtp::validate($user->email, $request->otp);
        // dd($isValid);
        if ($isValid->status) {
            session()->put('OTPSESSIONKEY', true);
        }

        $isvalid_string = $isValid ? 'true' : 'false';

        if (auth()->check() && session()->get('OTPSESSIONKEY')) {
            return redirect()->intended('/');
        } else {
            $notification = array(
                'message' => 'Invalid OTP code Entered',
                'alert-type' => 'error'
            );
            return redirect('vapp/auth/otp')->with($notification);
        }
    }

    public function verifyOtpAndLogin(Request $request)
    {

        $user = auth()->user();
        $key = 'otp-attempts:' . $user->id;
        // $remaining = max(0, 5 - RateLimiter::attempts($key));

        // 1. Check if user is locked
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            OTPModel::where('identifier', $user->email)->where('is_valid', true)->delete();
            $notification = [
                'message' => "Too many invalid OTP attempts.",
                'alert-type' => 'danger'
            ];
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            return redirect()->route('login')->with($notification);
        }

        // 2. Verify OTP
        $isValid = SimplOtp::validate($user->email, $request->otp);

        // $isvalid_string = $isValid->status ? 'true' : 'false';

        if ($isValid->status) {
            // ✅ Success: reset attempts
            RateLimiter::clear($key);
            session()->put('OTPSESSIONKEY', true);

            if (auth()->check() && session()->get('OTPSESSIONKEY')) {
                return redirect()->intended('/');
            }
        }

        // 3. Invalid OTP → count attempt + lock if max reached
        RateLimiter::hit($key, 100); // lock for 15 minutes

        $remaining = 5 - RateLimiter::attempts($key);

        $notification = [
            'message' => "Invalid OTP code entered. Attempts left: {$remaining}",
            'alert-type' => 'warning',
        ];
        return redirect('auth/otp')->with($notification);
    }

    public function showOtp()
    {
        // $key = 'otp-attempts:' . auth()->id();
        // $remaining = RateLimiter::attempts($key);

        // appLog('AdminController::showOtp => key: ' . $key);
        // appLog('AdminController::showOtp => attempts: ' . RateLimiter::attempts($key));
        // appLog('AdminController::showOtp => remaining attempts: ' . $remaining);
        return view('auth.otp');
    }

    public function resendOTP()
    {
        // dd( Session::all());
        $user = auth()->user();
        if (config('settings.otp_enabled')) {

            $key = Str::lower($user->id);

            // Allow 3 attempts every 5 minutes
            if (RateLimiter::tooManyAttempts($key, 3)) {
                $seconds = RateLimiter::availableIn($key);
                $minutes = floor($seconds / 60);
                $remainingSeconds = $seconds % 60;

                $timeMessage = $minutes > 0
                    ? "{$minutes} minute(s) and {$remainingSeconds} second(s)"
                    : "{$remainingSeconds} second(s)";

                $notification = array(
                    'message' => 'Too many OTP requests. Try again in ' . $timeMessage,
                    'alert-type' => 'danger'
                );

                return redirect('/auth/otp')->with($notification);

                return response()->json([
                    'message' => 'Too many OTP requests. Try again in ' . $seconds . ' seconds.'
                ], 429);
            }

            // Hit the rate limiter
            RateLimiter::hit($key, 300); // 300 seconds = 5 minutes

            $otp = SimplOtp::generate($user->email);
            if ($otp->status === true) {
                $details = [
                    'otp_token' => $otp->token,
                    'body' => 'Your One-Time Password (OTP) is: ' . $otp->token,
                ];
                Mail::to($user->email)->send(new OtpMail($details));
            }
            $notification = array(
                'message' => 'We have a sent a new OTP code to your email, please check',
                'alert-type' => 'success'
            );

            return redirect('/auth/otp')->with($notification);
            // return redirect('tracki/auth/otp')->with('message', 'OTP re-sent to your email');
        }
    }

    public function signUp()
    {
        $events = Event::all();
        return view('auth.sign-up', compact('events'));
    }

    public function register($event_id)
    {

        $event = Event::findOrFail($event_id);
        return view('auth.register', compact('event'));
    }

    public function msSignUp()
    {
        $events = Event::all();
        $roles = Role::all();
        $fas = DrsFunctionalArea::all();
        $venues = Venue::all();
        return view('auth.ms-sign-up', compact('events', 'roles', 'fas', 'venues'));
    }

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function submitForgetPasswordForm(Request $request): RedirectResponse
    {
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

        $content = [
            'token'     => $token,
            'subject'   => 'Tracki: Reset Password Link',
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
        return view('tracki.auth.reset', ['token' => $token]);
    } //showResetPasswordForm

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

        return redirect('/tracki/auth/login')->with('message', 'Your password has been changed!');
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

        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->department_assignment_id = $request->department_id;
        $data->password = Hash::make($request->password);
        $data->department_assignment_id = $request->department_id;
        $data->functional_area_id = $request->functional_area_id;
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
        return Redirect::route('tracki.auth.signup')->with($notification);
        //mainProfileStore

    }

    public function store(Request $request)
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
        $data->department_assignment_id = $request->department_id;
        $data->functional_area_id = $request->functional_area_id;
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
        return Redirect::route('tracki.auth.signup')->with($notification);
        //mainProfileStore

    } // store

    public function reportList()
    {
        return view('tracki.report');
    }

    public function userProfile()
    {
        // first get the auth user
        $id = Auth::user()->id;
        $profileData = User::find($id);

        // dd($profileData);

        return view('tracki.profile-view', compact('profileData'));
    }


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
        appLog($draw . ' ' . $start . ' ' . $rowPerPage . ' ' . $columnIndex_arr . ' ' . $order_arr . ' ' . $search_arr);
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
        appLog($draw . ' ' . $start . ' ' . $rowPerPage . ' ' . $columnIndex_arr . ' ' . $order_arr . ' ' . $search_arr);
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
