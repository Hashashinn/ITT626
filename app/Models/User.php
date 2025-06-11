<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Department;
use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use \Znck\Eloquent\Traits\BelongsToThrough;
    use HasApiTokens, Notifiable;
    // Note
    // belongsTo(foreign Model class, primary, foreign column name connected to user table ) if foregn table hold primary id
    // belongsTo(foreign Model class, primary column name connected to user table , foreign key) if primary table hold foreign id


    public function getRouteKeyName()
    {
        return 'name';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }
    // public function roles()
    // {
    //     return $this->belongsToMany('App\Models\Role', 'role_users', 'user_id', 'role_id');
    // }

    public function role()
    {
        return $this->belongsTo(RoleUser::class, 'id', 'user_id');
        // return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }


    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    //public function hasAnyRole($roles)
    //{
    //    if (Is_array($roles)) {
    //        foreach ($roles as $role) {
    //            if ($this->hasRole($role)) {
    //                return true;
    //            }
    //        }
    //    } else {
    //        if ($this->hasRole($roles)) {
    //            return true;
    //        }
    //    }

    //    return false;
    //} 

    public function hasRole($role)
    {
        return $this->roles->contains('slug', $role);
    }
    //public static function hasRole($role)
    //{
    //    if (auth()->user()->roles()->first()->slug === $role) {
    //        return true;
    //    }
    //    return false;
    //}


    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'uid', 'id');
    }

    public function timeoff()
    {
        return $this->belongsTo(Timeoff::class, 'id', 'user_id');
    }

    public function captureOutside()
    {
        return $this->hasMany(UserActivityOutside::class, 'user_id', 'id');
    }
}