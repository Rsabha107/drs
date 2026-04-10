<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key','locale','name','subject','body','allowed_variables','active'
    ];

    protected $casts = [
        'allowed_variables' => 'array',
        'active' => 'boolean',
    ];

    // public function versions()
    // {
    //     return $this->hasMany(EmailTemplateVersion::class);
    // }

    public function attachments()
    {
        return $this->hasMany(EmailTemplateAttachment::class);
    }
}
