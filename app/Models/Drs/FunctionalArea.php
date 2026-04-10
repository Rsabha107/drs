<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FunctionalArea extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table='functional_areas';


    public function venues()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }


    // protected static function booted(){

    //     appLog(auth()->user()->functional_area_id);
    //     self::addGlobalScope(function(EloquentBuilder $builder){
    //         $builder->when(auth()->user()->functional_area_id, function ($query, $user_fa) {
    //             return $query->where('functional_areas.id', $user_fa);
    //         });
    //     });
    // }
}
