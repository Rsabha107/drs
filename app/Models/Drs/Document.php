<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    use HasFactory;
    protected $guarded = [];
    protected $table = 'documents';

    protected $fillable = [
        'participant_id','category','disk','path','original_name','mime','size','created_by'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
