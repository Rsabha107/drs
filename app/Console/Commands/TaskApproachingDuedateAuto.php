<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Facades\Log;


class TaskApproachingDuedateAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-approaching-duedate-auto';

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
        //\
        appLog("testing this scheduler");

        // $taskData = Task::join('events', 'events.id', '=', 'tasks.event_id')
        // ->join('department', 'department.id', '=', 'tasks.department_assignment_id')
        // ->join('person', 'person.id', '=', 'tasks.assignment_id')
        // ->join('task_status', 'task_status.id', '=', 'tasks.status_id')
        // ->leftjoin('colors', 'colors.id', '=', 'tasks.color_id')
        // ->whereRaw('datediff(tasks.due_date, CURRENT_DATE) < 0')
        // ->where('tasks.progress', '<', 1)
        // ->orderBy('tasks.start_date', 'asc')
        // ->get(([
        //     'events.name as project_name',
        //     'tasks.name as task_name',
        //     'tasks.assignment_to_id',
        //     'department.name as department_name',
        //     'person.name as person_name',
        //     'task_status.name as status_name',
        //     'tasks.start_date',
        //     'tasks.due_date',
        //     'tasks.budget_allocation',
        //     'tasks.actual_budget_allocated',
        //     'tasks.event_id',
        //     'tasks.id',
        //     'tasks.duration',
        //     'tasks.progress as progress',
        //     'colors.name as color',
        //     'tasks.parent',
        //     'tasks.description',
        // ]));


    }
}
