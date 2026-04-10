<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'venues';

    public function matches()
    {
        return $this->hasMany(EventMatch::class, 'venue_id');
    }
}
