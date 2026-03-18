<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'teacher_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Link to users table (login account)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Classes handled by teacher
    public function classes()
    {
        return $this->hasMany(Classes::class, 'teacher_id');
    }

    // Subjects handled (through classes)
    public function subjects()
    {
        return $this->hasManyThrough(
            Subject::class,
            Classes::class,
            'teacher_id', // FK in classes
            'id',         // FK in subjects
            'id',         // PK in teachers
            'subject_id'  // FK in classes
        );
    }

    // Schedules (through classes)
    public function schedules()
    {
        return $this->hasManyThrough(
            Schedule::class,
            Classes::class,
            'teacher_id',
            'class_id',
            'id',
            'id'
        );
    }
}