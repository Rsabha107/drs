<?php

namespace App\Http\Controllers;

use App\Mail\AccessGrantedMail;
use App\Mail\AccountCreationMail;
use App\Models\Gms\Guardian;
use App\Models\SignedUrlToken;
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

        Log::info('UserController@store - Request: ' . json_encode($request->all()));

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        $message = '
            [
                "name.required" => "Name is required",
                "email.required" => "Email is required",
                "email.email" => "Provide a valid email",
                "email.unique" => "Email already exists",
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

            Log::info('Assigning roles: ' . json_encode($intRoles));

            // if ($request->roles) {
            //     $user->assignRole($intRoles);
            // }Cat123456!  

            if (!empty($request->event_id)) {
                foreach ($request->event_id as $key => $event) {
                    Log::info('Attaching event ID: ' . $event);
                    // Log::info('User ID: ' . $user->id);
                    // Log::info('Event ID: ' . $event);
                    $user->events()->attach($request->event_id[$key]);
                }
            }

            if (!empty($request->venue_id)) {
                foreach ($request->venue_id as $key => $venue) {
                    Log::info('Attaching venue ID: ' . $venue);
                    // Log::info('User ID: ' . $user->id);
                    // Log::info('Venue ID: ' . $venue);
                    $user->venues()->attach($request->venue_id[$key]);
                }
            }

            if (!empty($request->functional_area_id )) {
                foreach ($request->functional_area_id as $key => $functional_area) {
                    Log::info('Attaching functional area ID: ' . $functional_area);
                    // Log::info('User ID: ' . $user->id);
                    // Log::info('Functional Area ID: ' . $functional_area);
                    $user->fa()->attach($request->functional_area_id[$key]);
                }
            }

            // Mark the signed URL token as used
            if ($request->has('token')) {
                $token = $request->input('token');
                Log::info('Looking for token: ' . $token);
                
                $urlToken = SignedUrlToken::where('token', $token)->first();
                if ($urlToken) {
                    $urlToken->markAsUsed();
                    Log::info('Marked signed URL token as used: ' . $urlToken->token);
                } else {
                    Log::warning('Token not found in database: ' . $token);
                }
            } else {
                Log::info('No token found in request');
            }

            $notification = array(
                'message'       => 'User created successfully',
                'alert-type'    => 'success'
            );

            if (config('settings.send_notifications')) {
                $eventNames = $user->events()->exists()
                    ? $user->events->pluck('name')->implode(', ')
                    : 'N/A';
                $venueNames = $user->venues()->exists()
                    ? $user->venues->pluck('title')->implode(', ')
                    : 'N/A';
                $faNames = $user->fa()->exists()
                    ? $user->fa->pluck('title')->implode(', ')
                    : 'N/A';
                $details = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $request->password,
                    'url' => config('app.url'),
                    'event' => $eventNames,
                    'venue' => $venueNames,
                    'functional_area' => $faNames,
                ];
                // Send email notification
                Mail::to($user->email)->send(new AccountCreationMail($details));
                // SendAccountCreationEmailJob::dispatch($details);
            }

            return Redirect::route('login')->with($notification);
        } catch (\Exception $e) {
            Log::info('Validation error in UserController@store: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
        //mainProfileStore

    }

    public function msStore(Request $request)
    {

        Log::info('UserController@msStore - Request: ' . json_encode($request->all()));
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                // 'phone' => 'nullable|max:15',
                'event_id' => 'required',
                'roles' => 'required|array|min:1',
            ];

            if (config('settings.functional_area_user_management')) {
                $rules['fa_id'] = 'required|array|min:1';
            }

            $message = '
            [
                "event_id.required" => "At least one event must be selected",
                "name.required" => "Name is required",
                "email.required" => "Email is required",
                "email.email" => "Provide a valid email",
                "email.unique" => "Email already exists",
                // "phone.max" => "Phone number cannot exceed 15 characters",
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
            // $user->phone = $request->phone;
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
                    Log::info('Event ID: ' . $data);
                    $user->events()->attach($request->event_id[$key]);
                }
            }

            if ($request->fa_id) {
                foreach ($request->fa_id as $key => $data) {
                    $user->fa()->attach($request->fa_id[$key]);
                }
            }

            if ($request->venue_id) {
                foreach ($request->venue_id as $key => $data) {
                    $user->venues()->attach($request->venue_id[$key]);
                }
            }

            Log::info('Assigning roles: ' . json_encode($intRoles));

            // Mark the signed URL token as used
            if ($request->has('token')) {
                $token = $request->input('token');
                Log::info('Looking for token: ' . $token);
                
                $urlToken = SignedUrlToken::where('token', $token)->first();
                if ($urlToken) {
                    $urlToken->markAsUsed();
                    Log::info('Marked signed URL token as used: ' . $urlToken->token);
                } else {
                    Log::warning('Token not found in database: ' . $token);
                }
            } else {
                Log::info('No token found in request');
            }

            $notification = array(
                'message'       => 'User created successfully',
                'alert-type'    => 'success'
            );

            if (config('settings.send_notifications')) {
                $eventNames = $user->events()->exists()
                    ? $user->events->pluck('name')->implode(', ')
                    : 'N/A';
                $venueNames = $user->venues()->exists()
                    ? $user->venues->pluck('title')->implode(', ')
                    : 'N/A';
                $faNames = $user->fa()->exists()
                    ? $user->fa->pluck('title')->implode(', ')
                    : 'N/A';
                $details = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'event' => $eventNames,
                    'venue' => $venueNames,
                    'functional_area' => $faNames,
                    'role' => 'Customer',
                    'url' => config('app.url'),
                ];
                // Send email notification
                Mail::to($user->email)->send(new AccessGrantedMail($details));
            }

            DB::commit();
            return Redirect::route('login')->with($notification);
        } catch (\Exception $e) {
            Log::error('Validation error in UserController@store: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
        //mainProfileStore

    }
}
