<?php

namespace App\Http\Controllers\GeneralSettings;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $company = Company::first();
        return view ('mds.general.settings.company.edit', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd('createEvent');
        $op = new Company();

        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'website' =>['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error = true;
            // $message = implode($validator->errors()->all('<div>:message</div>'));  // use this for json/jquery
            $message = $validator->messages();
            $notification = array(
                'message'       => $message,
                'alert-type'    => 'error'
            );
            dd(Session
            ::has('message'));
            return redirect()->back()->withErrors($message)->withInput();
        }

        $op->name = $request->name;
        $op->email = $request->email;
        $op->phone = $request->phone;
        $op->website = $request->website;
        $op->created_by = $request->user()->id;
        $op->updated_by = $request->user()->id;
        $op->save();

        $notification = array(
            'message'       => 'Company created successfully',
            'alert-type'    => 'success'
        );

        $error = false;
        $message = 'Company ' . $op->name . ' successfully created';
        // return response()->json([
        //     'error' => false,
        //     'message' => 'Project ' . $project->name . ' created successfully ',
        // ]);
        // Toastr::success('Has been add successfully :)','Success');
        // return response()->json([
        //     'error' => $error,
        //     'message' => $message,
        // ]);

         return redirect()->route('mds.general.settings.company')->with($notification);
    } // store

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
        // dd('createEvent');
        $op = Company::first();

        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'website' =>['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error = true;
            // $message = implode($validator->errors()->all('<div>:message</div>'));  // use this for json/jquery
            $message = $validator->messages();
            $notification = array(
                'message'       => $message,
                'alert-type'    => 'error'
            );
            dd(Session
            ::has('message'));
            return redirect()->back()->withErrors($message)->withInput();
        }

        $op->name = $request->name;
        $op->email = $request->email;
        $op->phone = $request->phone;
        $op->website = $request->website;
        $op->created_by = $request->user()->id;
        $op->updated_by = $request->user()->id;
        $op->save();

        $notification = array(
            'message'       => 'Company updated successfully',
            'alert-type'    => 'success'
        );

        // $error = false;
        // $message = 'Company ' . $op->name . ' successfully created';
        // return response()->json([
        //     'error' => false,
        //     'message' => 'Project ' . $project->name . ' created successfully ',
        // ]);
        // Toastr::success('Has been add successfully :)','Success');
        // return response()->json([
        //     'error' => $error,
        //     'message' => $message,
        // ]);

         return redirect()->route('mds.general.settings.company')->with($notification);
    } // store

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
