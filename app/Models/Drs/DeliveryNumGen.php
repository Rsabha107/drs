<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNumGen extends Model
{
    use HasFactory;
    protected $table = 'rs_number_gen';
    protected $guarded = [];

}
