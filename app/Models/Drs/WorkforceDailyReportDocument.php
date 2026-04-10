<?php

namespace App\Models\Drs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkforceDailyReportDocument extends Model
{
    //
    use HasFactory;
    protected $guarded = [];
    protected $table = 'workforce_daily_report_documents';

    public $timestamps = false; // <-- Add this

    public function report()
    {
        return $this->belongsTo(WorkforceDailyReport::class, 'report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
