<?php

namespace App\Exports;

use App\Models\Wdr\WorkforceDailyReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkforceDailyReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'REFERENCE NUMBER',
            'EVENT',
            'VENUE',
            'REPORTED BY',
            'REPORT DATE',
            'DAY TYPE',
            'DEMAND OF DAY',
            'ATTENDED',
            'ATTENDANCE PERCENTAGE',
            'MEALS ORDERED VOLUNTEERS',
            'MEALS REDEEMED VOLUNTEERS',
            'VOLUNTEERS MEAL PERCENTAGE',
            'MEALS ORDERED STAFF',
            'MEALS REDEEMED STAFF',
            'STAFF MEAL PERCENTAGE',
            'INCIDENTS',
            'OTHER NOTES',
            'CREATED AT',
        ];
    }
    public function collection()
    {
        $ops = WorkforceDailyReport::all();
        $ops->transform(function ($op) {
            return [
                'reference_number' => $op->reference_number,
                'event' => $op->event->name,
                'venue' => $op->venue->title,
                'reported_by' => $op->reportedBy?->name,
                'report_date' => format_date($op->report_date),
                'day_type' => $op->dayType?->title,
                'demand_of_day' => $op->demand_of_day,
                'attended' => $op->attended,
                'attendance_percentage' => $op->attendance_percentage,
                'meals_ordered_volunteers' => $op->meals_ordered_volunteers,
                'meals_redeemed_volunteers' => $op->meals_redeemed_volunteers,
                'volunteers_meal_percentage' => $op->volunteers_meal_percentage,
                'meals_ordered_staff' => $op->meals_ordered_staff,
                'meals_redeemed_staff' => $op->meals_redeemed_staff,
                'staff_meal_percentage' => $op->staff_meal_percentage,
                'incidents' => $op->incidents,
                'other_notes' => $op->other_notes,
                'created_at' => $op->created_at,
            ];
        });
        return $ops;
    }
}
