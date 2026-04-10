<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateAttachment extends Model
{
    protected $fillable = ['email_template_id','disk','path','original_name','size'];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class);
    }
}
