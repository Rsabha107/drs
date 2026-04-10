<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\GeneralSettings\GlobalAttachment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Person;
use App\Models\Status;
use App\Models\Task;
use App\Models\TaskFileUpload;
use App\Models\TaskNote;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UtilController extends Controller
{
    //
    public static function getEventBudgetDetails($id)
    {
        appLog('UtilController::getEventBudgetDetails with id: ' . $id);
        // dd($id);
        $eventBudget = Event::find($id)?->budget_allocation;
        $totalTaskBudgetUsed = DB::table('tasks')
            ->select(DB::raw("sum(tasks.actual_budget_allocated) as sum_actual_budget"))
            ->where('event_id', '=', $id)
            ->get();

        $data_arr = [];

        $data_arr = (object) [
            "eventbudget" => $eventBudget,
            "task_total_budget" => $totalTaskBudgetUsed[0]->sum_actual_budget,
        ];

        return $data_arr;
    }

    public function getSumTaskProgress($id)
    {
        $user_department = auth()->user()->department_assignment_id;

        $sumTaskProgress = DB::table('tasks')
            ->select(DB::raw("sum(tasks.progress) as sum_progress"))
            ->where('event_id', '=', $id)
            ->when($user_department, function ($query, $user_department) {
                return $query->where('tasks.department_assignment_id', $user_department);
            })
            ->first();

        return $sumTaskProgress;
    }

    public function isTasksCompleted($id)
    {
        $status = false;

        // $taskCount = DB::table('tasks')
        //     ->where('event_id', '=', $id)
        //     ->count();

        $task = Task::where('event_id', '=', $id);

        $taskCount = $task->count();
        $sumTaskProgress = $task->sum('progress');

        if ($taskCount == 0) {
            $completionPercent = 0;
        } else {
            $completionPercent = $sumTaskProgress / $taskCount;
        }

        if ($completionPercent == '1') {
            $status = true;
            appLog('task completion: ' . $sumTaskProgress / $taskCount);
        }

        $data = [
            'status' => $status,
            'progress' => $completionPercent,
        ];

        return $data;
    }

    public static function taskNotesExists($id)
    {

        $ret = false;
        $taskNotes = TaskNote::where('task_notes.task_id', $id)->exists();

        if ($taskNotes) {
            Log::alert('taskNotesExists Task id: ' . $id . ' Yes');
            $ret = true;
        }

        return ($ret);
    }

    public static function taskFilesExists($id)
    {

        $ret = false;
        $taskFiles = TaskFileUpload::where('task_files.task_id', $id)->exists();

        if ($taskFiles) {
            $ret = true;
        }

        return ($ret);
    }

    public function updateProjectStatus($id)
    {
        $is_project_completed = $this->isTasksCompleted($id);
        // dd($is_project_completed);
        $status = Status::all();
        appLog($is_project_completed);
        if ($is_project_completed['status']) {
            appLog('project: ' . $id . ' is ' . config('tracki.project_status.completed'));
            Event::where('id', $id)
                ->update([
                    'event_status' => config('tracki.project_status.completed'),
                ]);
        } else {
            appLog('project: ' . $id . ' is ' . config('tracki.project_status.completed'));

            Event::where('id', $id)
                ->update([
                    'event_status' => config('tracki.project_status.inprogress'),
                ]);
        }
    }

    public static function getDateDiff($d1, $d2)
    {

        $start_date_d = Carbon::parse($d1)->format('d/m/y');
        $end_date_d = Carbon::parse($d2);
        $duration =  now()->diffInDays($end_date_d, false);

        return ($duration);
    }
    public static function getQrCode($id)
    {
        $qr_code = QrCode::size(50)->generate($id);
        return ($qr_code);
    }

    public static function getAssignedToName($id)
    {

        $id_array = explode(",", $id);

        $assignedTo = Person::whereIn('id', $id_array)
            //  ->where('source', '=','ASGTO')
            ->get([
                'person.name as assigned_to_name',
                'person.email_address',
            ]);

        return ($assignedTo);
    }

    public static function getAssignedToEmail($id)
    {

        $id_array = explode(",", $id);

        $assignedToEmail = Person::whereIn('id', $id_array)->pluck('email_address')->toArray();


        return ($assignedToEmail);
    }

    public function save_files(Request $request, $user_id)
    {
        appLog('UtilController::save_files');
        appLog('request: '. json_encode($request->all()));
        appLog('user_id: ' . $user_id);

        // $id = Auth::user()->id;
        // $task = Task::findOrFail($request->task_id);
        $data = new GlobalAttachment();

        // dd($request->task_id);
        // @unlink(public_path('upload/instructor_images/' . $data->photo));

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
            $data->user_id = $user_id;
            $data->employee_id = ($request->employee_id) ? $request->employee_id : null;
            $data->model_id = $user_id;
            $data->model_name = $request->model_name;
            $data->description = $request->description;

            $data->save();
        }


        return ($data);
    }//save_files

    public static function serve(GlobalAttachment $file)
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
            appLog('filepath: ' . $filepath);
            appLog('file: ' . $file->file_name);
            //  dd($filepath);
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
    // return redirect()->back();

    public static function showImage(GlobalAttachment $file)
    {
        // if(Auth::user() && Auth::id() === $file->user->id) {
        // Here we don't use the Storage facade that assumes the storage/app folder
        // So filename should be a relative path inside storage to your file like 'app/userfiles/report1253.pdf'

        // $contents = Storage::disk('private')->url('app/userfiles/bank/'.$file->file_name);

        // $size = Storage::disk('private')->size($file->file_name);
        // dd($size);
        // dd($contents);
        if (Auth::check()) {
            // $globalAttachment = GlobalAttachment::find(66);
            appLog('inside check'.$file->id);
            $filepath = storage_path($file->file_path . $file->file_name);
            appLog('filepath: ' . $filepath);
            appLog('file: ' . $file->file_name);
            //  dd($filepath);
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

}
