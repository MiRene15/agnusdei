<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'teacher_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'department',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'teacher_id');
    }

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

    public function subjects()
    {
        return $this->hasManyThrough(
            Subject::class,
            Classes::class,
            'teacher_id',
            'id',
            'id',
            'subject_id'
        );
    }
}