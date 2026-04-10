<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class ChartsController extends Controller
{
    //
    public function eventDash(){

        $taskData = Task::join('department', 'department.id', '=', 'tasks.department_assignment_id')
        ->select('department.name', DB::raw("sum(tasks.actual_budget_allocated) as value"))
        ->groupBy('department.name')
        ->get();

        return view('/charts/charts',['Data' => $taskData]);
    }
    public function pieChart()
    {
        // $taskData = DB::table('tasks')->select('name', 'actual_budget_allocated as value')->get();
        $taskData = Task::join('department', 'department.id', '=', 'tasks.department_assignment_id')
        ->select('department.name', DB::raw("sum(tasks.actual_budget_allocated) as value"))
        ->groupBy('department.name')
        ->get();

        appLog($taskData);

      $Data = array
                  (
                    "0" => array
                                    (
                                      "value" => 335,
                                      "name" => "Apple",
                                    ),
                    "1" => array
                                    (
                                      "value" => 310,
                                      "name" => "Orange",
                                    )
                                    ,
                    "2" => array
                                    (
                                      "value" => 234,
                                      "name" => "Grapes",
                                    )
                                    ,
                    "3" => array
                                    (
                                      "value" => 135,
                                      "name" => "Banana",
                                    )
                  );
        return view('/charts/piechart2',['Data' => $taskData]);
    }
}
