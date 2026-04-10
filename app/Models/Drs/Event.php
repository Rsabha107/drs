<?php

namespace App\Models\Drs;

use App\Models\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';

    // protected static function booted(){
    //     appLog(auth()->user()->functional_area_id);
    //     self::addGlobalScope(function(EloquentBuilder $builder){
    //         $builder->when(session()->get('workspace_id'), function ($query, $workspace) {
    //             return $query->where('events.workspace_id', $workspace);
    //         });
    //     });
    // }
    // protected $casts = [
    //     'start_time' => 'datetime: H:i',
    //     'end_time' => 'datetime: H:i',
    //   ];
    protected $appends = ["open"];

    public function getOpenAttribute()
    {
        return true;
    }


    public function documents()
    {
        return $this->hasMany(EventDocument::class);
    }

    public function qidDocuments()
    {
        return $this->hasMany(EventDocument::class)
            ->where('category', 'qid');
    }

    public function active_status()
    {
        return $this->belongsTo(GlobalStatus::class, 'active_flag');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'venue_event', 'event_id', 'venue_id');
    }
}
