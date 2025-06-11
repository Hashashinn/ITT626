<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model

{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = 'attendances';
    protected $guarded = [];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function location()
    {
        return $this->belongsToThrough(
            Location::class, // Target model
            User::class, // Intermediate model
            [
                Location::class => 'id',
                User::class => 'id'
            ], // Local key for intermediate model and target model
            '',
            [
                User::class => 'uid',
                Location::class => 'location_id',
            ] // Foreign key column on the target table and intermediate table
        );
    }

    public function department()
    {
        return $this->belongsToThrough(
            Department::class, // Target model
            User::class, // Intermediate model
            [
                Department::class => 'id',
                User::class => 'id'
            ], // Local key for intermediate model and target model
            '',
            [
                User::class => 'uid',
                Department::class => 'department_id',
            ] // Foreign key column on the target table and intermediate table
        );
    }

    public function schedule()
    {
        return $this->belongsToThrough(
            Schedule::class, // Target model
            User::class, // Intermediate model
            [
                Schedule::class => 'id',
                User::class => 'id'
            ], // Local key for intermediate model and target model
            '',
            [
                User::class => 'uid',
                Schedule::class => 'schedule_id',
            ] // Foreign key column on the target table and intermediate table
        );
    }
}
