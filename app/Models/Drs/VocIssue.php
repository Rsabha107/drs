<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocIssue extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'voc_issues';

    function report()
    {
        return $this->belongsTo(VenueMatchReport::class, 'report_id');
    }
}
