<?php

namespace App\Models\GeneralSettings;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'global_attachments';

    public function scopeIsActive($query, $flag)
    {
        return $query->where('archived', $flag);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
