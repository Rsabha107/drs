<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\GeneralSettings\GlobalAttachment;
use App\Models\Drs\FunctionalArea;
use App\Models\Drs\Event;
use App\Models\Drs\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')->select('group_name')->groupby('group_name')->get();

        return $permission_groups;
    }

    public static function getpermissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('id', 'name')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
            }
            return $hasPermission;
        }
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'user_event', 'user_id', 'event_id');
    }

    public function fa()
    {
        return $this->belongsToMany(FunctionalArea::class, 'user_fa', 'user_id', 'fa_id');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'user_venues', 'user_id', 'venue_id');
    }

    public function file_attach()
    {
        return $this->hasOne(GlobalAttachment::class, 'model_id'); //->where('model_name', 'users');
    }

    public function attachments()
    {
        return $this->hasMany(GlobalAttachment::class, 'model_id', 'id')->where('model_name', 'users');
    }

    public function hasVenue(int $venueId): bool
    {
        return $this->venues()->where('venues.id', $venueId)->exists();
    }
}
