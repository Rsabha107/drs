<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Http\Controllers\Controller;

use App\Models\Drs\Event;
use App\Models\Drs\Venue;
use App\Models\User;
use App\Models\Drs\WorkforceDailyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function dashboardxx()
    {
        // dd('inside trackiDashboard');


        // if (session()->has('workspace_id')){
        //     dd('session for workspace: '.session()->get('workspace_id'));
        // }

        $events = Event::all();
        $venues = Venue::all();
        $wdrs = WorkforceDailyReport::all();
        $todays_wdrs = WorkforceDailyReport::whereDate('report_date', Carbon::today())->get();

        // dd($stats);
        return view('drs.admin.dashboard.index', [
            'wdrs' => $wdrs,
            'events' => $events,
            'venues' => $venues,
            'todays_wdrs' => $todays_wdrs,
        ]);
    }  //trackiDashboard

public function dashboard()
{
    $today = Carbon::today();

    // 🔹 Total reports
    $totalReports = WorkforceDailyReport::count();

    // 🔹 Today's reports
    $todayReports = WorkforceDailyReport::whereDate('report_date', $today)->count();

    // 🔹 Avg attendance %
    $avgAttendance = WorkforceDailyReport::whereNotNull('attendance_percentage')
        ->avg('attendance_percentage');

    // 🔹 Avg meal consumption %
    // (average of volunteer + staff meal percentages)
    $avgMealConsumption = WorkforceDailyReport::where(function ($q) {
            $q->whereNotNull('volunteer_meal_percentage')
              ->orWhereNotNull('staff_meal_percentage');
        })
        ->selectRaw('AVG((COALESCE(volunteer_meal_percentage,0) + COALESCE(staff_meal_percentage,0)) / 
                          NULLIF(
                              (volunteer_meal_percentage IS NOT NULL) + 
                              (staff_meal_percentage IS NOT NULL),
                              0
                          )) as avg_meal')
        ->value('avg_meal');

    return view('drs.admin.dashboard.index', [
        'totalReports'       => $totalReports,
        'todayReports'       => $todayReports,
        'avgAttendance'      => round($avgAttendance ?? 0, 2),
        'avgMealConsumption' => round($avgMealConsumption ?? 0, 2),
    ]);
}



}
