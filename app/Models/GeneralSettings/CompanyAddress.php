<?php

namespace App\Models\GeneralSettings;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "company_addresses";

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
