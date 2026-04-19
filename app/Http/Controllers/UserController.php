<?php

namespace App\Http\Controllers;

use App\Mail\AccessGrantedMail;
use App\Mail\AccountCreationMail;
use App\Models\Gms\Guardian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Finder\Glob;

class UserController extends Controller
{
    //
    protected $UtilController;

    public function __construct(UtilController $UtilController)
    {
        $this->UtilController = $UtilController;
    }


    public function store(Request $request)
    {

        appLog('UserController@store - Request: ' . json_encode($request->all()));

        $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|max:15',
                'password' => ['required', 'confirmed', Password::defaults()],
            ];

            $message = '
            [
                "name.required" => "Name is required",
                "email.required" => "Email is required",
                "email.email" => "Provide a valid email",
                "email.unique" => "Email already exists",
                "phone.required" => "Phone is required",
                "password.required" => "Password is required",
                "password.confirmed" => "Password confirmation does not match",
                "password.min" => "Password must be at least 8 characters",
                "password.max" => "Password must not exceed 16 characters",
            ]';

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
            }

        try {

            $activate_value = sha1(time() . config('global.key'));

            // @unlink(public_path('upload/instructor_images/' . $data->photo));
            // $id = Auth::user()->id;
            $user = new User();

            $user->employee_id = 0;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->usertype = 'user';
            $user->is_admin = 0;
            $user->role = 'user';

            $user->save();

            // $roles = $request->roles;
            $role_id = getRoleIdByLabel('Customer');
            // $roles = ['Customer']; // Customer role ;
            // $roles = [18]; // Customer role ;

            $intRoles = collect([$role_id])->map(function ($role) {
                return (int)$role;
            });

            $user->assignRole($intRoles);

            appLog('Assigning roles: ' . json_encode($intRoles));

            // if ($request->roles) {
            //     $user->assignRole($intRoles);
            // }Cat123456!  

            if (!empty($request->event_id)) {
                foreach ($request->event_id as $key => $event) {
                    appLog('Attaching event ID: ' . $event);
                    // appLog('User ID: ' . $user->id);
                    // appLog('Event ID: ' . $event);
                    $user->events()->attach($request->event_id[$key]);
                }
            }

            // if (!empty($request->functional_id)) {
            //     foreach ($request->functional_id as $key => $functional) {
            //         appLog('Attaching functional ID: ' . $functional);
            //         appLog('User ID: ' . $user->id);
            //         appLog('Functional ID: ' . $functional);
            //         $user->fa()->attach($request->functional_id[$key]);
            //     }
            // }

            // $this->UtilController->save_files($request, $data->id);

            $notification = array(
                'message'       => 'User created successfully',
                'alert-type'    => 'success'
            );

            if (config('settings.send_notifications')) {
                $eventNames = $user->events()->exists()
                    ? $user->events->pluck('name')->implode(', ')
                    : 'N/A';
                $details = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $request->password,
                ];
                // Send email notification
                Mail::to($user->email)->send(new AccountCreationMail($details));
                // SendAccountCreationEmailJob::dispatch($details);
            }

            return Redirect::route('login')->with($notification);
        } catch (\Exception $e) {
            appLog('Validation error in UserController@store: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
        //mainProfileStore

    }

    public function msStore(Request $request)
    {

        appLog('UserController@msStore - Request: ' . json_encode($request->all()));
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|max:15',
                'event_id' => 'required',
                'roles' => 'required|array|min:1',
            ];

            $message = '
            [
                "name.required" => "Name is required",
                "email.required" => "Email is required",
                "email.email" => "Provide a valid email",
                "email.unique" => "Email already exists",
                "phone.max" => "Phone number cannot exceed 15 characters",
                "client_id.required" => "Client selection is required",
            ]';

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
            }

            // @unlink(public_path('upload/instructor_images/' . $data->photo));
            // $id = Auth::user()->id;
            $user = new User();

            $generated_password = generateSecurePassword();
            $hashed_password = Hash::make($generated_password);
            $user->password = $hashed_password;
            $user->employee_id = 0;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            // $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->usertype = 'user';
            $user->is_admin = 0;
            $user->role = 'user';
            // $user->address = 'doha';
            $user->save();

            $roles = $request->roles;

            $intRoles = collect($roles)->map(function ($role) {
                return (int)$role;
            });
            if ($request->roles) {
                $user->assignRole($intRoles);
            }

            if ($request->event_id) {
                foreach ($request->event_id as $key => $data) {
                    appLog('Event ID: ' . $data);
                    $user->events()->attach($request->event_id[$key]);
                }
            }

            if ($request->fa_id) {
                foreach ($request->fa_id as $key => $data) {
                    $user->fa()->attach($request->fa_id[$key]);
                }
            }

            appLog('Assigning roles: ' . json_encode($intRoles));

            $notification = array(
                'message'       => 'User created successfully',
                'alert-type'    => 'success'
            );

            if (config('settings.send_notifications')) {
                $eventNames = $user->events()->exists()
                    ? $user->events->pluck('name')->implode(', ')
                    : 'N/A';
                $details = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'event' => $eventNames,
                    'role' => 'Customer'
                ];
                // Send email notification
                Mail::to($user->email)->send(new AccessGrantedMail($details));
            }

            DB::commit();
            return Redirect::route('login')->with($notification);
        } catch (\Exception $e) {
            appLog('Validation error in UserController@store: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
        //mainProfileStore

    }
}
