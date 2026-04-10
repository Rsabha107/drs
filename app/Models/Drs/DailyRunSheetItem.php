<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyRunSheetItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'daily_run_sheet_items';

    public function runSheet()
    {
        return $this->belongsTo(DailyRunSheet::class, 'run_sheet_id');
    }
}
