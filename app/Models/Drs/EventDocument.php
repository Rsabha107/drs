<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDocument extends Model
{
    //
    use HasFactory;
    protected $guarded = [];
    protected $table = 'event_documents';

    protected $fillable = [
        'event_id','disk','path','original_name','mime','size','created_by'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
