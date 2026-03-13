<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'admission_id',
        'student_number',
        'lrn',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'email',
        'phone',
        'address',
        'grade_level',
        'section',
        'school_year',
        'status',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function tuitionFees()
    {
        return $this->hasMany(TuitionFee::class);
    }
}