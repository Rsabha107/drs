<?php

namespace App\Imports;

use App\Models\Vms\VocIssue;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class VocIssuesImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function __construct(private ?int $reportId = null, private ?string $draftToken = null) {}

    public function model(array $row)
    {
        // Normalize headings (Excel headings must match these keys)
        // Example headers:
        // issue_id | raised_by | responsible_fa | category | status | title_description | location | time_raised

        // Skip rows with no meaningful data
        $hasAny = collect($row)->filter(fn($v) => trim((string)$v) !== '')->isNotEmpty();
        if (!$hasAny) return null;

        return new VocIssue([
            'report_id'          => $this->reportId,
            'draft_token'        => $this->draftToken,
            'issue_id'           => $row['issue_id'] ?? $row['issue id'] ?? null,
            'raised_by'          => $row['raised_by'] ?? $row['raised by'] ?? null,
            'responsible_fa'     => $row['responsible_fa'] ?? $row['responsible fa'] ?? null,
            'category'           => $row['category'] ?? null,
            'status'             => $row['status'] ?? null,
            'description'        => $row['title_description'] ?? $row['title / description'] ?? $row['title_description'] ?? null,
            'location'           => $row['location'] ?? null,
            'time_raised'        => $this->normalizeExcelTime($row['time_raised'] ?? $row['time raised'] ?? null),
        ]);
    }

    private function normalizeExcelTime($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel numeric time, e.g. 0.5416666667
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('g:i A');
        }

        // Already text like 1:00 PM
        // or maybe it's in 24h format like 13:00
        // convert to 24h format

        try {
            return Carbon::parse($value)->format('g:i A');
            // return Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}
