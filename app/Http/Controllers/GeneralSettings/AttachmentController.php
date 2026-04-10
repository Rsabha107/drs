<?php

namespace App\Http\Controllers\GeneralSettings;

use App\Http\Controllers\Controller;
use App\Models\Cms\OrderHeader;
use App\Models\Employee;
use App\Models\GeneralSettings\GlobalAttachment;
use App\Models\Order;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class AttachmentController extends Controller
{
    //
    public function index()
    {
        $employees = Employee::all();
        $model_names = GlobalAttachment::distinct()->get('model_name');
        return view('hr.admin.files.list', compact('employees', 'model_names'));
    }

    public function get($id)
    {
        $op = GlobalAttachment::all();
        return response()->json(['op' => $op]);
    }

    public function update(Request $request)
    {

        $rules = [
            'id' => ['required'],
            // 'title' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            appLog($validator->errors());
            $error = true;
            // $message = 'Relationship not create.';
            $message = implode($validator->errors()->all());
        } else {
            $user_id = Auth::user()->id;
            $op = GlobalAttachment::findOrFail($request->id);

            $error = false;
            $message = 'Relationship updated.';

            // $op->title = $request->title;
            $op->created_by = $user_id;
            $op->updated_by = $user_id;
            $op->save();
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }

    public function store(Request $request)
    {
        appLog('AttachementController::store');
        appLog('request: '. json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'file_name' => 'required|mimes:pdf,doc,docx,txt,rtf|max:2048',
        ]);

        if ($validator->fails()) {
            $error = true;
            // $message = 'EmployeeEntity not create.';
            $message = implode($validator->errors()->all());

            return response()->json([
                'error' => true,
                'message' => $message,
                'user_name' => null,
                'note_text' => null,
                'note_date' => null,
                'id' => null
            ]);
        } else {
            $id = Auth::user()->id;
            // $task = Task::findOrFail($request->task_id);
            $data = new GlobalAttachment();

            // dd($request->task_id);

            if ($request->file('file_name')) {
                $file = $request->file('file_name');
                $filename = rand() . date('ymdHis') . $file->getClientOriginalName();
                Storage::disk('private')->putFileAs($request->model_name, $file, $filename);
                // $file->store('private');
                // Storage::putFileAs();
                $data->file_name = $filename;
                $data->original_file_name = $file->getClientOriginalName();
                $data->file_extension = $file->getClientOriginalExtension();
                $data->file_size = $_FILES['file_name']['size']; //$request->file('file_name')->getSize();
                $data->file_path = '/app/private/' . $request->model_name . '/';
                $data->user_id = $id;
                $data->employee_id = ($request->employee_id) ? $request->employee_id : null;
                $data->model_id = $request->model_id;
                $data->model_name = $request->model_name;
                $data->description = $request->description;
            }

            $data->save();

            if ($request->action === 'update_order_status') {
                appLog('Updating order status for model_id: ' . $request->model_id);
                $order = OrderHeader::find($request->model_id);
                if ($order) {
                    appLog('Order found: ' . $order->id);
                    appLog('Updating order status to "Payment Submitted"');
                    $order_status_id = gerOrderStatusId('Payment Submitted');
                    $order->order_status_id = intval($order_status_id);
                    $order->save();
                }
            }

            $notification = array(
                'message'       => 'File added successfully',
                'alert-type'    => 'success'
            );

            // return response()->json(['success'=>'You have successfully upload file.']);
            return response()->json([
                'error' => false,
                'message' => 'file added successfully',
                'user_name' => Auth::user()->username, //$data->users->username,
                'original_file_name' => $data->original_file_name,
                'task_file_id' => $data->id,
                'file_size' => $data->file_size,
                'created_at' => format_date($data->created_at,  'H:i:s'),
                'file_extension' => $data->file_extension,
                'file_name' => $data->file_name,
                'file_path' => $data->file_path,
            ]);
        }
        // return redirect()->back();
    } //store


    public function serve(GlobalAttachment $file)
    {
        // if(Auth::user() && Auth::id() === $file->user->id) {
        // Here we don't use the Storage facade that assumes the storage/app folder
        // So filename should be a relative path inside storage to your file like 'app/userfiles/report1253.pdf'

        // $contents = Storage::disk('private')->url('app/userfiles/bank/'.$file->file_name);

        // $size = Storage::disk('private')->size($file->file_name);
        // dd($size);
        // dd($contents);
        if (Auth::check()) {
            $filepath = storage_path($file->file_path . $file->file_name);
            // dd($filepath);
            // return response()->file($contents);
            // return Storage::download('storage/app/userfiles/bank/'.$file->filename);
            return response()->file($filepath);
        } else {
            return abort('404');
        }

        // }else{
        // return abort('404');
        // }
    }


    public function list($id = null)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        // $op = EmployeeAttachment::orderBy($sort, $order);
        $employee = (request()->employee) ? request()->employee : "";
        $attachment_type = (request()->attachment_type) ? request()->attachment_type : "";

        $project = Project::find($id);

        // $op = GlobalAttachment::orderBy($sort, $order);
        $op = $project->attachments();
        $op = $op->orderBy($sort, $order);

        // dd($op);
        if ($search) {
            $op = $op->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($employee) {
            $op = $op->where(function ($query) use ($employee) {
                $query->where('employee_id', 'like', '%' . $employee . '%');
            });
        }

        if ($attachment_type) {
            $op = $op->where(function ($query) use ($attachment_type) {
                $query->where('model_name', 'like', '%' . $attachment_type . '%');
            });
        }

        $total = $op->count();

        //apply scope
        $op = $op->isActive('N');

        $op = $op->paginate(request("limit"))->through(function ($op) {

            $actions =
                '<a href="javascript:void(0)" class="btn btn-sm" data-table="global_file_table" data-id="' .
                $op->id .
                '" id="delete_global_file" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="bx bx-trash text-danger"></i></a></div></div>';

            // $profile_url = route('hr.admin.employee.profile', encrypt($op->employees->id));

            return [
                'id' => $op->id,
                'id1' => '<div class="ms-3">' . $op->id . '</div>',
                'description' => '<div class="ms-1">' . $op->description . '</div>',
                'type' => '<div class="ms-1">' . $op->model_name . '</div>',
                'original_file_name' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3"><a href="' . route('global.file.serve', $op->id) . '" target="_blank"> ' . $op->original_file_name . '</a></div>',
                'file_size' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . formatSizeUnits($op->file_size) . '</div>',
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

    public function delete($id)
    {
        appLog('GlobalAttachment::delete');
        $fileDetails = GlobalAttachment::find($id);
        appLog($fileDetails->file_name);
        $filepath = storage_path($fileDetails->file_path . $fileDetails->file_name);

        $model_id = $fileDetails->model_id;
        if (File::exists($filepath)) {
            File::delete($filepath);
        }
        // if (File::exists(public_path('storage/upload/event_files/' . $fileDetails->file_name))) {
        //     File::delete(public_path('storage/upload/event_files/' . $fileDetails->file_name));
        // }

        GlobalAttachment::where('id', '=', $id)->delete();

                if ($fileDetails->count() === 0 && $fileDetails->model_name === 'ORDERS') {
                $order = OrderHeader::find($model_id);
                if ($order) {
                    appLog('Order found: ' . $order->id);
                    appLog('Updating order status to "Payment Submitted"');
                    $order_status_id = gerOrderStatusId('Payment Pending');
                    $order->order_status_id = intval($order_status_id);
                    $order->save();
                }
            }

        appLog('File deleted successfully with count: ' . $fileDetails->count());
        $notification = array(
            'message'       => 'File deleted successfully',
            'alert-type'    => 'success'
        );
        return response()->json([
            'error' => false,
            'message' => 'file ' . $fileDetails->original_file_name . ' deleted successfully',
        ]);
        // Toastr::success('Has been add successfully :)','Success');
        // return redirect()->back()->with($notification);
    } // fileDelete
}
