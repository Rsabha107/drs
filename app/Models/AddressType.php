<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function address_types()
    {
        return $this->hasOne(AddressType::class, 'id', 'address_type_id');
    }
}
