<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\Department;
use App\Models\Drs\Event;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UtilController;
use App\Mail\SendUserCreationLinkMail;
use App\Models\Drs\FunctionalArea;
use App\Models\Drs\Venue;
use App\Services\SignedUserLinkGenerator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //

    public function profile()
    {
        $user = User::find(Auth::user()->id);
        $file = $user->file_attach;

        return view('wdr/admin/users/profile', compact('user', 'file'));
    }

        public function showForm()
    {
        $events = Event::all();
        $functional_areas = FunctionalArea::all();
        $venues = Venue::all();
        // $functional_areas = FunctionalArea::all();
        return view('drs.admin.users.invite-user', compact('events', 'functional_areas', 'venues'));
    }

    public function sendInvite(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'event_id' => 'required|exists:events,id',
            'functional_area_id' => 'nullable|exists:functional_areas,id',
            'venue_id' => 'nullable|exists:venues,id',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'event_id.required' => 'Please select an event.',
            'event_id.exists' => 'The selected event is invalid.',
            'functional_area_id.exists' => 'The selected functional area is invalid.',
            'venue_id.exists' => 'The selected venue is invalid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::info($validator->errors());
            $error = true;
            $type = 'success';
            $message = $validator->messages();
            return redirect()->back()->withErrors($message)->withInput();
        }

        $link = SignedUserLinkGenerator::generate($request->name, $request->email, $request->event_id, $request->functional_area_id, $request->venue_id, 30); // valid for 30 mins
        Mail::to($request->email)->send(new SendUserCreationLinkMail($link, $request->name));

        return redirect()->back()->with('success', 'Invitation sent successfully to ' . $request->email);
    }

    public function createViaLink(Request $request)
    {
        // Optional auto-creation if params are passed
        Log::info('Creating user via signed link. createViaLink');
        // if ($request->filled(['name', 'email']) ) {
        //     $user = User::firstOrCreate(
        //         ['email' => $request->email],
        //         [
        //             'name' => $request->name,
        //             'password' => bcrypt(Str::random(10)) // You can also send a password param
        //         ]
        //     );

        //     return response()->json([
        //         'message' => 'User created or already exists.',
        //         'user' => $user,
        //     ]);
        // }

        // Otherwise, show a creation form
        Log::info('No name or email provided, redirecting to signup form.');
        return redirect()->route('drs.auth.signup', [
            'name' => $request->query('name'),
            'email' => $request->query('email'),
            'event_id' => $request->query('event_id'),
            'functional_area_id' => $request->query('functional_area_id'),
            'venue_id' => $request->query('venue_id'),
        ]);
    }
    
    public function update(Request $request)
    {

        $id = Auth::user()->id;
        $user = User::find($id);

        $rule = [
            'file_name' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            $notification = array(
                'message' => $validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->back()
                ->withInput()
                ->with($notification);
        }

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('file_name')) {

            $file = $request->file('file_name');
            $fileNameWithExt = $request->file('file_name')->getClientOriginalName();
            // get file name
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('file_name')->getClientOriginalExtension();

            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            Log::info($fileNameWithExt);
            Log::info($filename);
            Log::info($extension);
            Log::info($fileNameToStore);

            // upload
            if ($user->photo != 'default.png') {
                Storage::delete('public/upload/profile_images/' . $user->photo);
            }

            $path = $request->file('file_name')->storeAs('public/upload/profile_images', $fileNameToStore);
            // $path = $file->move('upload/profile_images/', $fileNameToStore);
            Log::info($path);


        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $user->photo = $fileNameToStore;

        $user->save();

        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function updatePassword(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        $rules = [
            'password' => 'required|confirmed|min:8|max:16',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $notification = array(
                'message' => $validator->errors()->first(),
                'alert-type' => 'error'
            );

            return redirect()->back()
                ->withInput()
                ->with($notification);
        }

        if(!Hash::check($request->current_password, $user->password)){
            $notification = array(
                'message' => 'Old Password is incorrect',
                'alert-type' => 'error'
            );

            // Toastr::error('Old Password is incorrect','Error');
            return redirect()->back()->with($notification);
        }

        // $user->password = Hash::make($request->password);
        // $user->save();

        // $notification = array(
        //     'message' => 'Password Updated Successfully',
        //     'alert-type' => 'success'
        // );

        // return redirect()->back()->with($notification);
    }

}
