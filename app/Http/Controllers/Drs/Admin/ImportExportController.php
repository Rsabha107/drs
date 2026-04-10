<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Exports\BookingExport;
use App\Exports\BookingSlotExport;
use App\Exports\WorkforceDailyReportExport;
use App\Http\Controllers\Controller;
use App\Imports\BookingSlotImport;
use App\Models\Mds\BookingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showImportForm() {}


    public function export(Request $request)
    {
        // Log::info('Report export initiated by user: ' . auth()->user()->name);
        // Validate the request if needed
        Log::info('Exporting Report to Excel file');
        Log::info($request->all());

        $filters = $request->only([
            'export_event_filter',        // array
            'export_venue_filter',
            // 'export_rsp_filter',
            // 'export_client_group_filter',
            'export_date_range_filter',
            // 'export_booking_status_filter'
        ]);

        Log::info('Filters applied: ' . json_encode($filters));

        return Excel::download(new WorkforceDailyReportExport($filters), 'workforce_daily_report_export.xlsx');
    }

    public function import(Request $request) {}
}
