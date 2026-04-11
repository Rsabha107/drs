<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\GlobalStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Imports\PermissionImport;
use App\Models\Drs\Event;
use App\Models\Drs\FunctionalArea as DrsFunctionalArea;
use App\Models\Wdr\FunctionalArea;
use App\Models\Drs\Venue;
// use App\Models\Mds\CmsEvent;
use App\Notifications\NewUserNotification;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class RoleController extends Controller
{
    //
    // *********************************************** Permissions *********************************************************************
    public function listPermission()
    {
        $dataDetails = Permission::all();

        return view('sec.permissions.list', compact('dataDetails'));
    }

    public function importPermission()
    {
        $dataDetails = Permission::all();

        return view('sec.permissions.import');
    }

    public function ImportNowPermission(Request $request)
    {

        Excel::import(new PermissionImport, $request->file('import_file'));

        $notification = array(
            'message'       => 'Permission imported successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.perm.list')->with($notification);
    }

    public function addPermission()
    {

        $groups = PermissionGroup::all();

        return view('sec.permissions.add', compact('groups'));
    }
    public function createPermission(Request $request)
    {
        // dd('mainEvent');

        $rules = [
            'name' => 'unique:permissions',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('sec.perm.list')
                ->withInput()
                ->withErrors($validator);
        }

        $op = new Permission;

        $op->name = $request->name;
        $op->group_name = $request->group_name;
        $op->active_flag = "1";

        $op->save();

        $notification = array(
            'message'       => 'Permission created successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.perm.list')->with($notification);
    } // saveEvent

    public function editPermission($id)
    {
        // dd('mainEvent');
        $permissions = Permission::find($id);
        $groups = PermissionGroup::all();
        $status = GlobalStatus::all();

        return view('sec.permissions.edit', compact('permissions', 'groups', 'status'));
    } // editaudience


    public function updatePermission(Request $request)
    {
        //  dd('id:'.$request->id);
        $rules = [
            'name' => 'unique:permissions,name,' . $request->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('sec.perm.list')
                ->withInput()
                ->withErrors($validator);
        }

        Permission::where('id', '=', $request->id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
            'active_flag' => $request->active_flag,
        ]);


        $notification = array(
            'message'       => 'Permission updated successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.perm.list')->with($notification);
        // return view('');

    }

    public function deletePermission($id)
    {
        // dd('mainEvent');
        Permission::where('id', '=', $id)->delete();

        $notification = array(
            'message'       => 'Permission deleted successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.perm.list')->with($notification);
    }

    // *********************************************** Group Name *********************************************************************
    public function listGroupName($id)
    {
        // dd('mainEvent');
        $data = PermissionGroup::where('id', '=', $id)
            ->where('active_flag', '=', 1)
            ->get();
        //dd($data);
        $data_arr[] = [
            "id"      => $data->id,
            "name"    => $data->name,
        ];

        $response = ["retData"  => $data_arr];

        return response()->json($response);
    } // listGroupName

    // *********************************************** Group *********************************************************************
    public function listGroup()
    {
        $dataDetails = PermissionGroup::all();

        return view('sec.groups.list', compact('dataDetails'));
    }

    public function createGroup(Request $request)
    {
        // dd('mainEvent');

        $rules = [
            'name' => 'unique:permission_group',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('sec.groups.list')
                ->withInput()
                ->withErrors($validator);
        }



        $op = new PermissionGroup;

        $op->name = $request->name;
        $op->active_flag = "1";

        $op->save();

        $notification = array(
            'message'       => 'Group created successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.groups.list')->with($notification);
    } // saveEvent

    public function editGroup($id)
    {
        // dd('mainEvent');
        $data = PermissionGroup::find($id);
        //dd($data);
        return view('/sec/groups/edit', ['data' => $data]);
    } // editGroup


    public function updateGroup(Request $request)
    {
        //  dd('id:'.$request->id);
        PermissionGroup::where('id', '=', $request->id)->update([
            'name' => $request->name,
        ]);


        $notification = array(
            'message'       => 'Group updated successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.groups.list')->with($notification);
    }

    public function deleteGroup($id)
    {
        // dd('mainEvent');
        PermissionGroup::where('id', '=', $id)->delete();

        $notification = array(
            'message'       => 'Group deleted successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        // return Redirect::route('sec.groups.list')->with($notification);
        return redirect()->back()->with($notification);
    }

    // *********************************************** Role *********************************************************************
    public function listRole()
    {
        $dataDetails = Role::all();

        return view('sec.roles.list', compact('dataDetails'));
    }

    public function createRole(Request $request)
    {
        // dd('mainEvent');

        $rules = [
            'name' => 'unique:permissions',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('sec.roles.list')
                ->withInput()
                ->withErrors($validator);
        }

        $permission = Role::create([
            'name' => $request->name,
        ]);

        // $op = new PermissionRole;

        // $op->name = $request->name;
        // $op->group_name = $request->group_name;
        // $op->active_flag = "1";

        // $op->save();

        $notification = array(
            'message'       => 'Group created successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.roles.list')->with($notification);
    } // saveEvent

    public function editRole($id)
    {
        // dd('mainEvent');
        $data = Role::find($id);
        //dd($data);
        return view('/sec/roles/edit', ['data' => $data]);
    } // editGroup


    public function updateRole(Request $request)
    {
        //  dd('id:'.$request->id);
        Role::where('id', '=', $request->id)->update([
            'name' => $request->name,
        ]);


        $notification = array(
            'message'       => 'Role updated successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        return Redirect::route('sec.roles.list')->with($notification);
    }

    public function deleteRole($id)
    {
        // dd('mainEvent');
        Role::where('id', '=', $id)->delete();

        $notification = array(
            'message'       => 'Role deleted successfully',
            'alert-type'    => 'success'
        );

        // Toastr::success('Has been add successfully :)','Success');
        // return Redirect::route('sec.roles.list')->with($notification);
        return redirect()->back()->with($notification);
    }


    // *********************************************** Add Role Permission all method *********************************************************************
    //
    public function addRolePermission()
    {

        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('sec.rolesetup.add', compact('roles', 'permissions', 'permission_groups'));
    }

    public function createRolePermission(Request $request)
    {
        $data = array();
        $permissions = $request->permissions;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        } // end foreach

        $notification = array(
            'message'       => 'Role Permission added successfully',
            'alert-type'    => 'success'
        );
        return redirect()->route('sec.rolesetup.list')->with($notification);
    }

    public function listRolePermission()
    {

        $roles = Role::all();
        // dd($roles);
        return view('sec.rolesetup.list', compact('roles'));
    }

    public function editRolePermission($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('sec.rolesetup.edit', compact('role', 'permissions', 'permission_groups'));
    } // editRolePermission

    public function updateRolePermission(Request $request)
    {
        appLog('role id:'.$request->role_id);
        $role = Role::findOrFail($request->role_id);
        $permissions = $request->permissions;

        // Log::withContext('permissions:'.$permissions);

        $intPermissions = collect($permissions)->map(function ($permission) {
            return (int)$permission;
        });

        if (!empty($permissions)) {
            $role->syncPermissions($intPermissions);
        }

        $notification = array(
            'message'       => 'Role Permission updated successfully',
            'alert-type'    => 'success'
        );
        return redirect()->route('sec.rolesetup.list')->with($notification);
    } //updateRolePermission

    public function deleteRolePermission($id)
    {
        $role = Role::findOrFail($id);
        if (!is_null($role)) {
            $role->delete();
        }

        $notification = array(
            'message'       => 'Role Permission deleted successfully',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    } // deleteRolePermission


    // *********************************************** Admin User all method *********************************************************************

    public function listAdminUser()
    {

        $userdata = User::all();
        // dd($userdata);

        return view('sec.adminuser.list', compact('userdata'));
    } //listAdminUser

    public function addAdminUser()
    {
        $roles = Role::all();
        $events = Event::all();
        $venues = Venue::all();
        $functional_areas = DrsFunctionalArea::all();

        return view('sec.adminuser.add', compact('roles', 'events', 'venues', 'functional_areas'));
    }  // addAdminUser

    public function createAdminUser(Request $request)
    {

        $rules = [
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $user = new User();
        $token = Str::random(60);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->employee_id = 0;
        $user->password = Hash::make($request->password);
        $user->usertype = $request->usertype;
        $user->is_admin = $request->usertype == 'admin' ? 1 : 0;
        $user->role = $request->usertype;
        $user->status = $request->status;
        $user->address = 'doha';
        // $user->first_login_token = $token;
        // $user->first_login_flag = 1;

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
                $user->events()->attach($request->event_id[$key]);
            }
        }
       
        if ($request->venue_id) {
            foreach ($request->venue_id as $key => $data) {
                $user->venues()->attach($request->venue_id[$key]);
            }
        }

        if ($request->fa_id) {
            foreach ($request->fa_id as $key => $data) {
                $user->fa()->attach($request->fa_id[$key]);
            }
        }

        if (config('settings.send_notifications')) {
            appLog('Sending new user notification to ' . $user->email);
            $user->notify(new NewUserNotification($user));
        }

        $notification = array(
            'message'       => 'New user inserted successfully',
            'alert-type'    => 'success'
        );
        return redirect()->route('sec.adminuser.list')->with($notification);
    }

    public function editAdminUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $events = Event::all();
        $venues = Venue::all();
        $functional_areas = DrsFunctionalArea::all();

        return view('sec.adminuser.edit', compact('user', 'roles', 'events', 'venues', 'functional_areas'));
    }

    public function updateAdminUser(Request $request)
    {

        $user = User::findOrFail($request->user_id);

        // if ($request->usertype == 'admin'){
        //     $user->workspace_id = null;
        //     $user->department_assignment_id = null;
        // } else {
        //     $user->department_assignment_id = $request->department_id;
        //     $user->workspace_id = $request->workspace_id;
        // };

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        // $user->address = $request->address;
        $user->usertype = $request->usertype;
        $user->is_admin = $request->usertype == 'admin' ? 1 : 0;
        $user->role = $request->usertype;
        $user->status = $request->status;

        $user->save();

        $intRoles = collect($request->roles)->map(function ($role) {
            return (int)$role;
        });

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($intRoles);
        }

        $user->events()->detach();
        if ($request->event_id) {
            foreach ($request->event_id as $key => $data) {
                $user->events()->attach($request->event_id[$key]);
            }
        }

        $user->venues()->detach();
        if ($request->venue_id) {
            foreach ($request->venue_id as $key => $data) {
                $user->venues()->attach($request->venue_id[$key]);
            }
        }
        $user->fa()->detach();
        if ($request->fa_id) {
            foreach ($request->fa_id as $key => $data) {
                $user->fa()->attach($request->fa_id[$key]);
            }
        }

        $notification = array(
            'message'       => 'User updated successfully',
            'alert-type'    => 'success'
        );
        return redirect()->route('sec.adminuser.list')->with($notification);
    }

    public function manualUpdateAdminUser(Request $request)
    {

        $user = User::findOrFail($request->user_id);

        // dd($user);
        // $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);

            return ('assigned role ' . $request->roles . ' to user ' . $request->user_id);
        }

        // $notification = array(
        //     'message'       => 'Admin user updated successfully',
        //     'alert-type'    => 'success'
        // );
        // return redirect()->route('sec.adminuser.list')->with($notification);

        return ('ok now??');
    }

    public function deleteAdminUser($id)
    {
        $user = User::findOrFail($id);

        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message'       => 'Admin user deleted successfully',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
