<?php

namespace App\Models\Drs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'template_key','locale','to','cc','bcc','subject','body',
        'payload','attachments','status','error','sent_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime',
    ];
}

