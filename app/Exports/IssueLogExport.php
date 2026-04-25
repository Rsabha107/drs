<?php

namespace App\Exports;

use App\Models\Vms\Venue;
use App\Models\Vms\VenueMatchReport;
use App\Models\Vms\VocIssue;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class IssueLogExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $id;

    public function __construct($id = null)
    {

        $this->id = $id;
    }

    public function view(): View
    {
        $query = VocIssue::query()->where('report_id', $this->id);


        $issues = $query->orderBy('id')->get();

        // dd($issues);

        $report = VenueMatchReport::with(['venue', 'reportedBy'])->find($this->id);
        // dd($report);
        // $first = $issues->first();

        $meta = [
            'event'     => $report->event?->name ?? 'Event Name',
            'venue'     => $report->venue?->label ?? 'Venue Name',
            'date'      => $report->match_date ? Carbon::parse($report->match_date)->format('d-M') : now()->format('d-M'),
            'match'     => $report->match_number ?? '32',
            'ko_time'   => $report->match_time ? Carbon::parse($report->match_time)->format('H:i') : '19:00',
            'half_time' => $report->match_half_time ? Carbon::parse($report->match_half_time)->format('H:i') : '19:53',
        ];

        return view('drs.shared.exports.issue-log', [
            'meta' => $meta,
            'issues' => $issues,
        ]);
    }

    public function title(): string
    {
        return 'Issue Log';
    }
}
