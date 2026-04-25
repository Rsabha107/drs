<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MdTemplateItem extends Model
{
    use HasFactory;

    protected $table = 'md_template_items';
    protected $guarded = [];
}
