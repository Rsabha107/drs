<?php

namespace App\Exports;

use App\Models\Drs\DailyRunSheet;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyRunSheetExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $sheet = DailyRunSheet::with(['event', 'venue', 'match', 'items'])->findOrFail($this->id);

        return view('drs.drs.exports.run-sheet', [
            'sheet' => $sheet,
        ]);
    }

    public function title(): string
    {
        return 'Daily Run Sheet';
    }
}
