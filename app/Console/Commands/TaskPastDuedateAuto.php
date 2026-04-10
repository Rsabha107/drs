<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskPastDuedateMail;


class TaskPastDuedateAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-past-duedate-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        appLog("app:task-past-duedate-auto");
        // get the past due tasks

        $late_task_count = Task::join('events', 'events.id', '=', 'tasks.event_id')
            ->join('department', 'department.id', '=', 'tasks.department_assignment_id')
            ->join('person', 'person.id', '=', 'tasks.assignment_id')
            ->join('task_status', 'task_status.id', '=', 'tasks.status_id')
            ->leftjoin('colors', 'colors.id', '=', 'tasks.color_id')
            // ->where('tasks.event_id', '=', $id)
            ->whereRaw('datediff(tasks.due_date, CURRENT_DATE) < 0')
            ->where('tasks.progress', '<', 1)
            ->withoutGlobalScopes()
            ->count();

            $content = [
                'subject'       => 'Tracki: Late Tasks',
                'late_tasks'    => $late_task_count,
            ];

        appLog("number of late tasks: ". $late_task_count);

        Mail::to(['rsabha@gmail.com'])
        // Mail::to(['angie.aldakroury@dohaoasis.com','omajeed@printempsdoha.com','mnirmal@printempsdoha.com'])
            ->bcc('rsabha@gmail.com')
            ->queue(new TaskPastDuedateMail($content));

    }
}
